<script id="timesheets-template" type="text/x-handlebars-template">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Timesheet
                </div>

                <div class="panel-body">
                    <table class="table table-condensed table-hover" id="timesheet-table">
                        <thead>
                            <th>Day</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>In</th>
                            <th>Out</th>
                        </thead>
                        {{#each data.timesheets}}
                            <tr class="info" id="{{ day_of_week }}">
                                <td>{{ day_of_week }}</td>
                                <td>{{ @key }}</td>
                                <td>{{ total }}</td>
                                <td colspan="2">
                                    <button id="{{ day_of_week }}_button">{{ button }}</button>
                                </td>
                            </tr>
                            {{#each punches}}
                                <tr class="{{ day_of_week }}_punch {{ class }}">
                                    <td colspan="2"></td>
                                    <td>{{ total }}</td>
                                    <td>In: {{ in_time }}</td>
                                    <td>
                                        Out: {{#if out_time }}
                                            {{ out_time }}
                                        {{/if}}
                                    </td>
                                </tr>
                            {{/each}}
                        {{/each}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</script>