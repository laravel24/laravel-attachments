<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Attachment;
use App\Post;

class AttachmentTest extends TestCase
{

  public function setUp()
  {
    parent::setUp();
    Artisan::call('migrate');
    $this->cleanDirectories();
  }

  public function tearDown()
  {
    parent::tearDown();
    $this->cleanDirectories();
  }

  public function cleanDirectories()
  {
    Storage::disk('public')->deleteDirectory('uploads');
  }

  public function getFileForAttachment($attachment)
  {
    return dirname(__DIR__) . '/Fixtures/uploads/' . $attachment['name'];
  }

  private function callController($data = [])
  {
    $path = dirname(__DIR__) . '/Fixtures/demo.png';
    $file = new UploadedFile($path, 'demo.png', filesize($path), 'image/png', null, true);
    $post = Post::create([ 'name' => 'demo', 'content' => 'demo' ]);

    $default = [
      'attachable_type' => Post::class,
      'attachable_id' => $post->id,
      'image' => $file
    ];

    return $this->post(route('attachments.store'), array_merge($default, $data));
  }

  public function test_incorrect_data_attachable_type()
  {
    $response = $this->callController([ 'attachable_type' => 'Poooo' ]);
    $response->assertJsonStructure([ 'attachable_type' ]);
    $response->assertStatus(422);
  }

  public function test_incorrect_data_attachable_id()
  {
    $response = $this->callController([ 'attachable_id' => 3 ]);
    $response->assertJsonStructure([ 'attachable_id' ]);
    $response->assertStatus(422);
  }

  public function test_correct_data()
  {
    $response = $this->callController();
    $attachment = $response->json();

    $this->assertFileExists($this->getFileForAttachment($attachment));

    $response->assertJsonStructure([ 'id', 'url' ]);
    $this->assertContains('/uploads/', $attachment['url']);
    $response->assertStatus(200);
  }

  public function test_delete_attachment_delete_file()
  {
    $response = $this->callController();
    $attachment = $response->json();

    $this->assertFileExists($this->getFileForAttachment($attachment));
    Attachment::find($attachment['id'])->delete();
    $this->assertFileNotExists($this->getFileForAttachment($attachment));
  }

  public function test_delete_post_delete_all_attachments()
  {
    $response = $this->callController();
    $attachment = $response->json();
    factory(Attachment::class, 3)->create();

    $this->assertFileExists($this->getFileForAttachment($attachment));
    $this->assertEquals(4, Attachment::count());

    Post::first()->delete();
    $this->assertFileNotExists($this->getFileForAttachment($attachment));
    
    $this->assertEquals(3, Attachment::count());
  }

  public function test_change_post_content_attachments_are_deleted()
  {
    $response = $this->callController();
    $attachment = $response->json();
    factory(Attachment::class, 3)->create();

    $this->assertFileExists($this->getFileForAttachment($attachment));
    $this->assertEquals(4, Attachment::count());

    $post = Post::first();
    $post->content = "<img src=\"#{$attachment['url']}\" /> bla bla bla";
    $post->save();
    $this->assertEquals(4, Attachment::count());
    
    $post->content = "";
    $post->save();

    $this->assertFileNotExists($this->getFileForAttachment($attachment));
    $this->assertEquals(3, Attachment::count());
  }

}
