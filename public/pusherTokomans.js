var pusher = new Pusher('c7361a97e7eadb2f7fe4', {
    cluster: 'ap1'
});
const currentUrl = window.location.pathname.split('/').toString().replace(/,/g, '');

fetch('/theAPI', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
})
    .then(response => response.json())
    .then(data => {
        let userReceives = data;
        let acceptedAdmin = ['admin', 'adminedit'];
        var booladmin = false;
        acceptedAdmin.forEach(element => {
            if (currentUrl.includes(element)) {
                booladmin = true;
            }
            if (currentUrl == 'adminadd') {
                booladmin = false;
            }
        });
        if (booladmin) {
            var UrID = currentUrl.replace('adminedit', '');
            var whosEdit = false;
            channelAdmin = pusher.subscribe('admin-channel');
            channelAdmin.bind('my-event', function (data) {
                whosEdit = data.id;
                let userSends = data.user;
                if (userSends.toString().trim().toLowerCase() != userReceives.toString().trim().toLowerCase()) {
                    if (currentUrl.includes('adminedit')) {
                        if (whosEdit == UrID) {
                            createModal(data.massage);
                        }
                    }
                    else
                        if (whosEdit != UrID) {
                            createModal(data.massage);
                        }
                }
            });

        }
        fetch('/permissionAPI/' + currentUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(response => response.json()).then(data => {
            let div = document.getElementById('mainApp');
            let channel = false;
            let check = data;
            let gimmemodal = false;
            if (check != "inactive") {
                channel = pusher.subscribe('my-channel');
            }
            if (channel) {
                channel.bind('my-event', function (data) {
                    let userSends = data.user;
                    if (userSends.toString().trim().toLowerCase() != userReceives.toString().trim().toLowerCase()) {
                        if (check == data.id && check != "active") {
                            gimmemodal = true;
                        }
                        if (check == "active") {
                            gimmemodal = true;
                        }
                        if (data.excepturl.split(',').includes(currentUrl)) {
                            gimmemodal = false;
                        }
                    }
                    if (gimmemodal) {
                        createModal(data.massage);
                    }
                });
            }
        });

    })