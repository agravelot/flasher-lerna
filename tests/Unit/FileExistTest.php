<?php

namespace Tests\Unit;

use App\Rules\FileExist;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileExistTest extends TestCase
{
    private FileExist $rule;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('s3');
        $this->rule = new FileExist('s3', '');
    }

    /** @test */
    public function the_file_exists_rule_can_be_validated(): void
    {
        Storage::disk('s3')->put('real.txt', 'content');
        $this->assertTrue($this->rule->passes('media_path', 'real.txt'));
        $this->assertFalse($this->rule->passes('media_path', 'notfound.txt'));
    }
}
