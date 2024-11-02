<div id="dragscrolltable">
    <div class="table-bordered table-reporting">
        {!! $table !!}
    </div>
</div>

<script>
    $(document).ready(function () {

        @if(Session::get('scroll'))
        $('.table-responsive').animate({
            scrollTop: $("#client{{Session::get('scrollId')}}").offset().top-400
        }, 'slow');
        console.log({{Session::get('scrollId')}});
        console.log($('.table-responsive').animate({
            scrollTop: $("#client{{Session::get('scrollId')}}").offset().top-400
        }, 'slow'));
        @endif

        $( "textarea[name='client.comment']" ).change(function(e) {
            clientid = parseInt(e.target.id.replace(/\D+/g,""));
            $.ajax({
                url: "clients/updateComment",
                type: 'PUT',
                data: {clientid:clientid, comment:$(this).val(), "_token":"{{ csrf_token() }}"},
                success: function(result) {
                    console.log('success');
                }
            });
        });

        $(".colorReplace").each(function() {
            $(this).parent().parent().parent().css("background", $(this).css("background"));
        });

        $(".table td[data-column='organization']").hover(function() {
            $(this).children().children().children('.brush').css("display","block");
        }, function() {
            $(this).children().children().children('.brush').last().css("display","none");
        });
    });
</script>

