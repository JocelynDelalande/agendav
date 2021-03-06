<?php

/*
 * Copyright 2014 Jorge López Pérez <jorge@adobo.org>
 *
 *  This file is part of AgenDAV.
 *
 *  AgenDAV is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  any later version.
 *
 *  AgenDAV is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with AgenDAV.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AgenDAV\Repositories;

use AgenDAV\Data\Share;


/**
 * Interface for a shares repository
 *
 * @author Jorge López Pérez <jorge@adobo.org>
 */
interface SharesRepository
{
    /**
     * Returns all calendars shared with a user
     *
     * @param string $username  User name
     * @return Array Array of Share's
     */
    public function getSharesFor($username);

    /**
     * Returns all grants that have been given to a calendar
     *
     * @param string $path  Path to the calendar
     * @return Array Array of Share's
     */
    public function getSharesOnCalendar($path);

    /**
     * Stores a grant on the database
     *
     * @param AgenDAV\Data\Share $share  Share object
     */
    public function save(Share $share);

    /**
     * Removes a grant for a calendar
     *
     * @param AgenDAV\Data\Share $share  Share object
     */
    public function remove(Share $share);
}
