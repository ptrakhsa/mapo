@extends('layouts.docs-panel')
@section('content')
    <h3>Validate event</h3>
    lokasi controller:
    app\Http\Controllers\Api\ApiOrganizerController.php @validateEvent
    <pre>set @start = '2022-06-30 06:45:46', @end = '2022-07-03 06:45:46', @pos = GEOMETRYFROMTEXT('POINT(110.36588792192126 -7.791903826254844)', 4326);

    SELECT events.id, ST_DISTANCE_SPHERE(events.position, @pos, 4326)
    as distance, events.name , (SELECT submitted_events.status 
                            FROM submitted_events  WHERE events.id = submitted_events.event_id ORDER BY id DESC LIMIT 1) as last_status 
    FROM events 
    WHERE ST_DISTANCE_SPHERE(events.position, @pos, 4326) <= 0.5 AND (SELECT submitted_events.status FROM submitted_events WHERE events.id = submitted_events.event_id ORDER BY id DESC LIMIT 1) = 'verified' AND (events.start_date between @start and @end
    OR events.end_date between @start and @end
    OR (@start between events.start_date and events.end_date)
);</pre>
    <h3>Add event</h3>
    lokasi:
    app\Http\Controllers\Organizer\OrganizerEventController.php @store
    <pre>
        INSERT INTO events(`id`,`name`,`description`,`content`,`start_date`,`end_date`,`location`,`position`,`photo`,`link`,`organizer_id`,`category_id`,`popular_place_id`,`created_at`,`updated_at`,`deleted_at`) VALUES ('','event candi abang','candi abang yayayay','asoyy','2022-07-12 16:03:53','2022-07-15 16:03:53', 'candiabang',ST_GeomFromText('POINT(110.49139855375093 -7.7511342341190215)'),'Photo Gw','/assets/images/samples/jump.jpg',1,1,7,'','','');
</pre>
    <h4>Reject event</h4>
    lokasi:
    app\Http\Controllers\Admin\AdminEventController.php @rejectedEvent
    <pre>
        UPDATE `submitted_events` 
        SET `status` = 'rejected',
        `reason` = 'fix your location!',
        `deleted_at` = NULL 
        WHERE 
        `submitted_events`.`event_id` = 1;
    </pre>
    <h4>Verify event</h4>
    <pre>
        UPDATE `submitted_events` 
        SET `status` = 'verified',
        `reason` = '',
        `deleted_at` = NULL WHERE 
        `submitted_events`.`event_id` = 30;
    </pre>
    <h4>Takedown event</h4>
    <h4>Mark event as done</h4>
    <h4>Sort event by start_date</h4>
    <pre>
            SELECT * FROM `events` ORDER BY `events`.`start_date` DESC
        </pre>
@endsection
