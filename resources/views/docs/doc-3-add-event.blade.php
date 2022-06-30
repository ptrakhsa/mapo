@extends('layouts.docs-panel')
@section('content')
<h3>Validate event</h3>
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
<h4>Reject event</h4>
<h4>Verify event</h4>
<h4>Takedown event</h4>
<h4>Mark event as done</h4>
@endsection