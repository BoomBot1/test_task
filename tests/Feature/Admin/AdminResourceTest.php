<?php

use App\Filament\Resources\System\AdminResource;
use App\Filament\Resources\System\AdminResource\Pages\CreateAdmin;
use App\Filament\Resources\System\AdminResource\Pages\EditAdmin;
use App\Filament\Resources\System\AdminResource\Pages\ListAdmins;
use App\Filament\Resources\System\AdminResource\Pages\ViewAdmin;
use App\Models\Admin;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::factory()->admin()->create();
    $this->actingAs($this->admin);

    $this->doesntHaveSoftDeletes = !$this->isSoftDeletableModel(new Admin);

    $this->resource = AdminResource::class;
    $this->listPage = ListAdmins::class;
    $this->createPage = CreateAdmin::class;
    $this->editPage = EditAdmin::class;
    $this->viewPage = ViewAdmin::class;
});

describe('Pages', function () {
    it('index', function () {
        $this->get($this->resource::getUrl('index'))->assertSuccessful();
    });

    it('create', function () {
        $this->get($this->resource::getUrl('create'))->assertSuccessful();
    });

    it('view', function () {
        $record = Admin::factory()->create();

        $this->get($this->resource::getUrl('view', ['record' => $record]))
            ->assertSuccessful();
    });

    it('edit', function () {
        $record = Admin::factory()->create();

        $this->get($this->resource::getUrl('edit', ['record' => $record]))
            ->assertSuccessful();
    });
});

describe('List page', function () {
    it('renders table columns', function () {
        $columns = ['name', 'email'];
        $testable = livewire($this->listPage);

        foreach ($columns as $column) {
            $testable->assertCanRenderTableColumn($column);
        }
    });

    it('renders table actions', function () {
        $actions = [ViewAction::class, EditAction::class];
        $testable = livewire($this->listPage);
        $record = Admin::factory()->create();

        foreach ($actions as $action) {
            $testable->assertTableActionEnabled($action, $record);
        }
    });

    it('searches using columns', function () {
        $columns = ['name', 'email'];
        $testable = livewire($this->listPage);

        $records = Admin::factory(10)->create();
        $searchRecord = $records->shuffle()->first();

        foreach ($columns as $column) {
            $value = $searchRecord->{$column};
            $expectedRecords = $records->where($column, '=', $value);
            $unexpectedRecords = $records->where($column, '!=', $value);

            $testable
                ->searchTable($value)
                ->assertCanSeeTableRecords($expectedRecords)
                ->assertCanNotSeeTableRecords($unexpectedRecords);
        }
    });

    it('doesnt show trashed records by default', function () {
        $records = Admin::factory(3)->create();
        $records->push($this->admin);
        $trashedRecords = Admin::factory(6)->trashed()->create();

        livewire($this->listPage)
            ->assertCanSeeTableRecords($records)
            ->assertCanNotSeeTableRecords($trashedRecords)
            ->assertCountTableRecords($records->count());
    })->skip(fn() => $this->doesntHaveSoftDeletes);
});

describe('Create page', function () {
    it('creates record', function () {
        $record = Admin::factory()->make();

        livewire($this->createPage)
            ->fillForm([
                'name' => $record->name,
                'email' => $record->email,
                'password' => $record->password,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Admin::class, [
            'name' => $record->name,
            'email' => $record->email,
        ]);
    });

    it('validates input', function () {
        livewire($this->createPage)
            ->fillForm([
                'name' => null,
                'email' => null,
                'password' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    });
})->skip(fn() => $this->createPage === null);

describe('View page', function () {
    it('renders record', function () {
        $record = Admin::factory()->create();

        livewire($this->viewPage, ['record' => $record->getRouteKey()])
            ->assertFormSet([
                'name' => $record->name,
                'email' => $record->email,
                'password' => null,
            ]);
    });
})->skip(fn() => $this->viewPage === null);

describe('Edit page', function () {
    it('retrieves data from record', function () {
        $record = Admin::factory()->create();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->assertFormSet([
                'name' => $record->name,
                'email' => $record->email,
                'password' => null,
            ]);
    });

    it('updates record data', function () {
        $record = Admin::factory()->create();
        $newData = Admin::factory()->make();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->fillForm([
                'name' => $newData->name,
                'email' => $newData->email,
                'password' => $newData->password,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($record->refresh())
            ->name->toBe($newData->name)
            ->email->toBe($newData->email);
    });

    it('validates input', function () {
        $record = Admin::factory()->create();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->fillForm([
                'name' => null,
                'email' => null,
                'password' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    });

    it('deletes record', function () {
        $record = Admin::factory()->create();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->callAction(DeleteAction::class);

        if ($this->doesntHaveSoftDeletes) {
            $this->assertModelMissing($record);
        } else {
            expect($record->refresh())
                ->deleted_at->not->toBeNull();
        }
    });

    it('deletes and restores record', function () {
        $record = Admin::factory()->create();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->callAction(DeleteAction::class);

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->callAction(RestoreAction::class);

        expect($record->refresh())
            ->deleted_at->toBeNull();
    })->skip(fn() => $this->doesntHaveSoftDeletes);

    it('deletes and force deletes record', function () {
        $record = Admin::factory()->create();

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->callAction(DeleteAction::class);

        livewire($this->editPage, ['record' => $record->getRouteKey()])
            ->callAction(ForceDeleteAction::class);

        $this->assertModelMissing($record);
    })->skip(fn() => $this->doesntHaveSoftDeletes);
})->skip(fn() => $this->editPage === null);
