@extends('layouts.docs-panel')
@section('content')
    <h3>How to find events</h3>
    <p>
        this query used for get events groupped by location so one location has many events, this query needs current center
        map
        position as <b>latitude longitude</b>
    </p>
    <pre>
            select events.location,
                ST_DISTANCE_SPHERE(`position`, ST_GeomFromText('POINT(110.370529 -7.797068)', 4326)) AS distance,
                Y(position)                                                                          AS lat,
                X(position)                                                                          AS lng,
                ST_ASGEOJSON(position)                                                               as geometry,
                GROUP_CONCAT(JSON_OBJECT('id', events.id
                                        , 'name', events.name
                                        , 'start_date' ,DATE_FORMAT(events.start_date, '%e %b, %I:%i')
                                        , 'description', events.description
                                        , 'photo', events.photo
                                        , 'lat', ST_Y(events.position)
                                        , 'lng', ST_X(events.position)
                                        , 'category_name', categories.name
                                        , 'category_id', categories.id
                                    ) ORDER BY events.start_date ASC) AS events
            from `events`
                inner join `categories` on `categories`.`id` = `events`.`category_id`
            where `events`.`deleted_at` is null
            and (select status from submitted_events se where se.event_id = events.id order by id desc limit 1) = 'verified'
            group by `position`
        </pre>
    <p>result of this query parsed to geojson as below</p>
    <img src="/assets/images/contents/event-geojson.png" class="img-fluid" />
@endsection
