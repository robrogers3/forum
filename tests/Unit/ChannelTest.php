<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    protected $channel;

    public function setUp()
    {
        parent::setUp();
        $this->channel = factory('App\Channel')->create();
    }

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $thread = create('App\Thread', ['channel_id' => $this->channel->id]);

        $this->assertTrue($this->channel->threads->contains($thread));
    }

    /** @test */
    public function a_channel_can_be_created()
    {
        $channel = create('App\Channel');

        $this->assertDatabaseHas('channels', [
            'name' => $channel->name,
            'slug' => $channel->slug,
            'id' => $channel->id
        ]);
    }

    /** @test */
    public function a_channel_must_be_unique()
    {
        $channel = create('App\Channel');

        $this->assertDatabaseHas('channels', [
            'name' => $channel->name,
            'slug' => $channel->slug,
            'id' => $channel->id
        ]);
        
        $this->expectException('Illuminate\Database\QueryException');
            
        $channel2 = create('App\Channel', ['name' => $channel->name]);
    }
}
