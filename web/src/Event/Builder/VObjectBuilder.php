<?php

namespace AgenDAV\Event\Builder;

/*
 * Copyright 2014-2015 Jorge López Pérez <jorge@adobo.org>
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

use AgenDAV\Uuid;
use AgenDAV\DateHelper;
use AgenDAV\Event;
use AgenDAV\Event\Builder;
use AgenDAV\Event\VObjectEvent;
use AgenDAV\Event\VObjectEventInstance;
use Sabre\VObject\Component\VCalendar;

class VObjectBuilder implements Builder
{
    /** @var \DateTimeZone */
    protected $timezone;

    /**
     * Creates a new VObjectBuilder, specifying the user default timezone
     *
     * @param \DateTimeZone $timezone
     */
    public function __construct(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * Creates an empty Event object
     *
     * @param string $uid Optional UID for this event
     * @return \AgenDAV\Event
     */
    public function createEvent($uid = null)
    {
        $vcalendar = new VCalendar();

        if ($uid === null) {
            $uid = \AgenDAV\Uuid::generate();
        }

        $event = new VObjectEvent($vcalendar);
        $event->setUid($uid);

        return $event;
    }

    /**
     * Creates an empty EventInstance object
     *
     * @param \AgenDAV\Event $event Event this instance will be attached to
     * @return \AgenDAV\EventInstance
     * @throws \LogicException If $event has no UID assigned
     */
    public function createEventInstanceFor(\AgenDAV\Event $event)
    {
        $result = $event->createEventInstance();

        return $result;
    }

    /**
     * Creates an EventInstance object after receiving an array of properties
     * with the following keys:
     *
     * summary
     * location
     * start_date
     * start_time
     * end_date
     * end_time
     * allday
     * description
     * class
     * transp
     * TODO: recurrence rules, reminders, recurrence-id
     *
     * @param \AgenDAV\Event $event Parent event
     * @param array $attributes
     * @return \AgenDAV\EventInstance
     */
    public function createEventInstanceWithInput(\AgenDAV\Event $event, array $attributes)
    {
        $instance = $this->createEventInstanceFor($event);

        // Try to assign most simple properties
        foreach ($attributes as $key => $value) {
            $this->assignProperty($instance, $key, $value);
        }

        $this->setStartAndEnd($instance, $attributes);

        // TODO reminders

        return $instance;
    }


    protected function assignProperty(VObjectEventInstance $instance, $key, $value)
    {
        switch ($key) {
            case 'summary':
                $instance->setSummary($value);
                break;
            case 'location':
                $instance->setLocation($value);
                break;
            case 'description':
                $instance->setDescription($value);
                break;
            case 'class':
                $instance->setClass($value);
                break;
            case 'transp':
                $instance->setTransp($value);
                break;
            case 'rrule':
                $instance->setRepeatRule($value);
                break;
        }
    }

    protected function setStartAndEnd(VObjectEventInstance $instance, array $attributes)
    {
        $is_all_day = !empty($attributes['allday']) && $attributes['allday'] === 'true';

        if ($is_all_day === true) {
            $utc = new \DateTimeZone('UTC');
            $start = DateHelper::frontEndToDateTime($attributes['start'], $utc);
            $end = DateHelper::frontEndToDateTime($attributes['end'], $utc);

            $end->modify('+1 day');
        } else {
            $start = DateHelper::frontEndToDateTime($attributes['start'], $this->timezone);
            $end = DateHelper::frontEndToDateTime($attributes['end'], $this->timezone);
        }

        $instance->setStart($start, $is_all_day);
        $instance->setEnd($end, $is_all_day);
    }
}
