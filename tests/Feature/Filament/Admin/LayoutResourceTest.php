<?php

namespace Tests\Feature\Filament\Admin;

use App\Enums\Orientation;
use App\Filament\Admin\Resources\LayoutResource;
use App\Filament\Admin\Resources\LayoutResource\Pages\CreateLayout;
use App\Filament\Admin\Resources\LayoutResource\Pages\EditLayout;
use App\Models\CustomComponent;
use App\Models\Customer;
use App\Models\Layout;
use App\Models\User;
use App\Support\Layouts\LayoutGrid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LayoutResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'narrowcasting.admin_emails' => ['admin@example.com'],
        ]);
    }

    public function test_admin_can_view_the_layout_index_page(): void
    {
        $admin = $this->makeAdminUser();

        $this->actingAs($admin, 'admin')
            ->get(LayoutResource::getUrl('index'))
            ->assertOk();
    }

    public function test_admin_can_create_a_layout_with_versioned_grid_data(): void
    {
        $admin = $this->makeAdminUser();
        $customer = Customer::factory()->create();
        $customComponent = CustomComponent::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $grid = [
            'id' => 'root',
            'direction' => 'v',
            'split' => 40,
            'children' => [
                [
                    'id' => 'n1',
                    'direction' => null,
                    'split' => 50,
                    'children' => [],
                    'component' => 'text',
                ],
                [
                    'id' => 'n2',
                    'direction' => null,
                    'split' => 50,
                    'children' => [],
                    'component' => 'custom:'.$customComponent->id,
                ],
            ],
        ];

        $this->actingAs($admin, 'admin');

        Livewire::test(CreateLayout::class)
            ->set('data.title', 'Lobby Layout')
            ->set('data.orientation', Orientation::Landscape->value)
            ->set('data.customers', [$customer->id])
            ->set('data.grid', $grid)
            ->call('create')
            ->assertHasNoErrors()
            ->assertRedirect(LayoutResource::getUrl('index'));

        $layout = Layout::query()->where('title', 'Lobby Layout')->firstOrFail();

        $this->assertSame(Orientation::Landscape, $layout->orientation);
        $this->assertSame(1, $layout->grid['version']);
        $this->assertSame(
            ['text', 'custom:'.$customComponent->id],
            LayoutGrid::assignedComponentKeys($layout->grid),
        );
        $this->assertSame([$customer->id], $layout->customers()->pluck('customers.id')->all());
    }

    public function test_admin_cannot_save_unavailable_custom_components_into_a_layout(): void
    {
        $admin = $this->makeAdminUser();
        $customer = Customer::factory()->create();

        $this->actingAs($admin, 'admin');

        Livewire::test(CreateLayout::class)
            ->set('data.title', 'Broken Layout')
            ->set('data.orientation', Orientation::Landscape->value)
            ->set('data.customers', [$customer->id])
            ->set('data.grid', [
                'id' => 'root',
                'direction' => null,
                'split' => 50,
                'children' => [],
                'component' => 'custom:999999',
            ])
            ->call('create')
            ->assertHasErrors(['data.grid']);
    }

    public function test_editing_a_layout_preserves_orientation_and_normalizes_legacy_grid_data(): void
    {
        $admin = $this->makeAdminUser();
        $layout = Layout::factory()->create([
            'orientation' => Orientation::Portrait,
            'grid' => [
                'id' => 'root',
                'direction' => null,
                'split' => 50,
                'children' => [],
                'component' => 'text',
            ],
        ]);

        $this->actingAs($admin, 'admin');

        Livewire::test(EditLayout::class, ['record' => $layout->getKey()])
            ->set('data.title', 'Updated Portrait Layout')
            ->set('data.orientation', Orientation::Landscape->value)
            ->set('data.grid', [
                'id' => 'root',
                'direction' => null,
                'split' => 50,
                'children' => [],
                'component' => 'image',
            ])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(LayoutResource::getUrl('index'));

        $layout->refresh();

        $this->assertSame('Updated Portrait Layout', $layout->title);
        $this->assertSame(Orientation::Portrait, $layout->orientation);
        $this->assertSame(1, $layout->grid['version']);
        $this->assertSame(['image'], LayoutGrid::assignedComponentKeys($layout->grid));
    }

    public function test_new_base_components_are_available_for_layouts(): void
    {
        $allowedKeys = LayoutResource::getAllowedComponentKeys();

        $this->assertContains('logo', $allowedKeys);
        $this->assertContains('map', $allowedKeys);
        $this->assertContains('stat', $allowedKeys);

        $this->assertSame(
            [],
            LayoutGrid::invalidComponentKeys(
                [
                    'id' => 'root',
                    'direction' => 'v',
                    'split' => 50,
                    'children' => [
                        [
                            'id' => 'n1',
                            'direction' => null,
                            'split' => 50,
                            'children' => [],
                            'component' => 'logo',
                        ],
                        [
                            'id' => 'n2',
                            'direction' => 'h',
                            'split' => 50,
                            'children' => [
                                [
                                    'id' => 'n3',
                                    'direction' => null,
                                    'split' => 50,
                                    'children' => [],
                                    'component' => 'map',
                                ],
                                [
                                    'id' => 'n4',
                                    'direction' => null,
                                    'split' => 50,
                                    'children' => [],
                                    'component' => 'stat',
                                ],
                            ],
                        ],
                    ],
                ],
                $allowedKeys,
            ),
        );
    }

    protected function makeAdminUser(): User
    {
        return User::factory()->create([
            'email' => 'admin@example.com',
            'customer_id' => Customer::factory(),
        ]);
    }
}
