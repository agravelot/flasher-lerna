<?php

declare(strict_types=1);

namespace Tests;

trait WithClearQueryLog
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    protected function setUpQueryLog(): void
    {
        $this->db = $this->app->make('db');
        $this->db->flushQueryLog();
        $this->db->enableQueryLog();
    }

    protected function tearDownQueryLog(): void
    {
        $this->db->flushQueryLog();
        $this->db->disableQueryLog();
    }
}
