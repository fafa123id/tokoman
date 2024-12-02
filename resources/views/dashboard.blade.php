<x-mainApp-layout>
    @include('layouts.homeContent')
</x-mainApp-layout>
<script>
    var pusher = new Pusher('c7361a97e7eadb2f7fe4', {
        cluster: 'ap1'
    });
    let div = document.getElementById('mainApp');

    var channel = pusher.subscribe('report-channel');
    channel.bind('my-event', function (data) {
        createModal(JSON.stringify(data));
    });
</script>

