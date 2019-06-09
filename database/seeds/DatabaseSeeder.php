<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TEMPLATE SEEDER
        factory(App\Models\Template\Eloquent\TemplateModel::class, 10)->create()->each(
            function ($template) {
                $template->attributes()->save(
                    factory(App\Models\Template\Eloquent\TemplateattributeModel::class)->make()
                );
                $template->links()->save(
                    factory(App\Models\Template\Eloquent\TemplatelinkModel::class)->make()
                );
                }
        );
        // CHECKLIST SEEDER
        factory(App\Models\Checklist\Eloquent\ChecklistModel::class, 10)->create()->each(
            function ($checklist) {
                $checklist->attributes()->save(
                    factory(App\Models\Checklist\Eloquent\ChecklistattributeModel::class)->make()
                );
                $checklist->links()->save(
                    factory(App\Models\Checklist\Eloquent\ChecklistlinkModel::class)->make()
                );
                }
        );
        // ITEM SEEDER
        factory(App\Models\Item\Eloquent\ItemModel::class, 10)->create()->each(
            function ($item) {
                $item->attributes()->save(
                    factory(App\Models\Item\Eloquent\ItemattributeModel::class)->make()
                );
                $item->links()->save(
                    factory(App\Models\Item\Eloquent\ItemlinkModel::class)->make()
                );
                }
        );
    }
}
