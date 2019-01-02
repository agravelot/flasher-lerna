<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests;

trait WithClearQueryLog
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    protected function setUpQueryLog()
    {
        $this->db = $this->app->make('db');
        $this->db->flushQueryLog();
        $this->db->enableQueryLog();
    }

    protected function tearDownQueryLog()
    {
        $this->db->flushQueryLog();
        $this->db->disableQueryLog();
    }
}
