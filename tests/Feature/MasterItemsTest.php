<?php

namespace Tests\Feature;

use App\Models\MasterItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasterItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_master_item_with_photo()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('item_photo.jpg');

        $response = $this->post('/master-items/form/new', [
            'nama' => 'Test Item',
            'harga_beli' => 1000,
            'laba' => 10,
            'supplier' => 'Tokopaedi',
            'jenis' => 'Obat',
            'foto' => $file,
        ]);

        $response->assertRedirect('/master-items');

        $this->assertDatabaseHas('master_items', [
            'nama' => 'Test Item',
            'harga_beli' => 1000,
            'laba' => 10,
            'supplier' => 'Tokopaedi',
            'jenis' => 'Obat',
        ]);

        $item = MasterItem::first();
        $this->assertNotNull($item->foto);
        Storage::disk('public')->assertExists($item->foto);
    }

    public function test_can_edit_master_item_and_replace_photo()
    {
        Storage::fake('public');

        $item = MasterItem::create([
            'kode' => '00001',
            'nama' => 'Old Item',
            'harga_beli' => 2000,
            'laba' => 15,
            'supplier' => 'Bukulapuk',
            'jenis' => 'Alkes',
            'foto' => 'items/old_photo.jpg',
        ]);

        // Place the dummy old photo in fake storage
        Storage::disk('public')->put('items/old_photo.jpg', 'fake content');
        Storage::disk('public')->assertExists('items/old_photo.jpg');

        $newFile = UploadedFile::fake()->image('new_photo.jpg');

        $response = $this->post("/master-items/form/edit/{$item->id}", [
            'nama' => 'Updated Item',
            'harga_beli' => 2500,
            'laba' => 20,
            'supplier' => 'Bukulapuk',
            'jenis' => 'Alkes',
            'foto' => $newFile,
        ]);

        $response->assertRedirect('/master-items');

        $item->refresh();
        $this->assertEquals('Updated Item', $item->nama);
        $this->assertNotEquals('items/old_photo.jpg', $item->foto);

        // Assert new file exists and old file is deleted
        Storage::disk('public')->assertExists($item->foto);
        Storage::disk('public')->assertMissing('items/old_photo.jpg');
    }

    public function test_can_download_master_items_excel()
    {
        $response = $this->get('/master-items/download-excel');
        $response->assertStatus(200);
        
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
