<div id="dragscrolltable">
    <div class="table-bordered table-reporting">
        {!! $table !!}
    </div>
</div>



<script>

    $(document).ready(function () {
        $('#screen-modal-asyncModal').on('shown.bs.modal', function (e) {
            $("textarea[name='event_fields[event_action]']").focus();
        })

        @if(Session::get('scroll'))
        $('.table-responsive').animate({
            scrollTop: $("#{{Session::get('scrollId')}}").offset().top-400
        }, 'slow');
        @endif

        $(".unavailableReport").each(function() {
            $(this).parent().parent().css("background","rgb(222 221 221)")
        });
        $(".reportStatusPass").each(function() {
            $(this).parent().parent().parent().css("background","rgb(109 109 109 / 61%)")
        });
        $(".reportStatusSuccess").each(function() {
            $(this).parent().parent().parent().css("background","rgb(40 167 69 / 80%)")
        });
        $(".reportStatusFailure").each(function() {
            $(this).parent().parent().parent().css("background","rgb(193 0 0 / 80%)")
        });

        $(".warningSalaries1").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon2\"><span style=\"background: rgb(255 149 157 / 54%);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s")))->translatedFormat('F')}}</span></span></span>");

        });
        $(".successSalaries1").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon2\"><span style=\"background: rgb(217 255 212);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s")))->translatedFormat('F')}}</span></span></span>");
        });

        $(".warningSalaries2").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon1\"><span style=\"background: rgb(255 149 157 / 54%);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s", strtotime("first day of -1 month"))))->translatedFormat('F')}}</span></span></span>");

        });
        $(".successSalaries2").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon1\"><span style=\"background: rgb(217 255 212);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s", strtotime("first day of -1 month"))))->translatedFormat('F')}}</span></span></span>");

        });

        $(".normalSalaries1").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon2\"><span style=\"background: rgb(195 195 195 / 54%);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s")))->translatedFormat('F')}}</span></span></span>");
        });

        $(".normalSalaries2").each(function() {
            $(this).parent().parent().append("<span> <span class=\"ribbon1\"><span style=\"background: rgb(195 195 195 / 54%);\">{{\Carbon\Carbon::parse(date(date("Y-m-01 H:i:s", strtotime("first day of -1 month"))))->translatedFormat('F')}}</span></span></span>");
        });

        $(".unavailableReportModal").each(function() {
            $(this).parent().parent().parent().css("background","rgb(222 221 221)")
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


