<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                2023 © TP
            </div>
            <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-md-block">
                    <a href="javascript: void(0);">About</a>
                    <a href="javascript: void(0);">Support</a>
                    <a href="javascript: void(0);">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->

</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->


</div>
<!-- END wrapper -->

<!-- bundle -->
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"--}}
{{--            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"--}}
{{--            crossorigin="anonymous"></script>--}}
<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('js/notify.min.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    let notifications = $('.simplebar-content');
    let icon = $('.noti-icon-badge');
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    let pusher = new Pusher('45126cb6d1fed70c47a3', {
        cluster: 'ap1',
        encrypted: true,
    });

    let channel = pusher.subscribe('orders');
    channel.bind('new-order', function (data) {
        let existingNotifications = notifications.html();
        let url = '{{ route('admin.orders.edit', '_id') }}';
        url = url.replace('_id', data.order.id);
        let newNotificationHtml = `
             <a href="${url}" class="dropdown-item notify-item">
                <div class="notify-icon bg-primary">
                    <i class="mdi mdi-comment-account-outline"></i>
                </div>
                <p class="notify-details">${data.message}</p>
            </a>
        `;
        notifications.html(newNotificationHtml + existingNotifications);
        console.log(newNotificationHtml)
        icon.removeClass('d-none');
        $.notify(`${data.message}`, "success");
    });

    let channel2 = pusher.subscribe('appointments');
    channel2.bind('new-appointment', function (data) {
        console.log(1);
        let existingNotifications = notifications.html();
        let url = '{{ route('admin.appointments.edit', '_id') }}';
        url = url.replace('_id', data.appointment.id);
        let newNotificationHtml = `
             <a href="${url}" class="dropdown-item notify-item">
                <div class="notify-icon bg-primary">
                    <i class="mdi mdi-comment-account-outline"></i>
                </div>
                <p class="notify-details">${data.message}</p>
            </a>
        `;
        notifications.html(newNotificationHtml + existingNotifications);

        icon.removeClass('d-none');
        $.notify(`${data.message}`, "success");
    });

    $(document).ready(function () {
        $('.notify-item').click(function () {
            this.remove();
        });
    });
</script>
@stack('js')
</body>

</html>
