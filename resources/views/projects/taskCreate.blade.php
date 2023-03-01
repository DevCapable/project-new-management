@if($project && $currentWorkspace)
    <form class="" method="post" action="@auth('web'){{ route('tasks.store',[$currentWorkspace->slug,$project->id]) }}@elseauth{{ route('client.tasks.store',[$currentWorkspace->slug,$project->id]) }}@endauth">
        @csrf
         <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-8">
                <label class="col-form-label">{{ __('Project')}}</label>
                <select class="form-control form-control-light select2" name="project_id" required>

                        <option value="{{$project->id}}">{{$project->name}}</option>

                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="col-form-label">{{ __('Milestone')}}</label>
                <select class="form-control form-control-light select2" name="milestone_id" id="task-milestone">
                    <option value="">{{__('Select Milestone')}}</option>
                    @foreach($project->milestones as $milestone)
                        <option value="{{$milestone->id}}">{{$milestone->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-8">
                <label class="col-form-label">{{ __('Title')}}</label>
                <input type="text" class="form-control form-control-light" id="task-title" placeholder="{{ __('Enter Title')}}" name="title" required>
            </div>
            <div class="form-group col-md-4">
                <label class="col-form-label">{{ __('Priority')}}</label>
                <select class="form-control form-control-light select2" name="priority" id="task-priority" required>
                    <option value="Low">{{ __('Low')}}</option>
                    <option value="Medium">{{ __('Medium')}}</option>
                    <option value="High">{{ __('High')}}</option>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label">{{ __('Assign To')}}</label>

                      <select class=" multi-select" id="assign_to" name="assign_to[]" data-toggle="select2" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}" required>
                    @foreach($users as $u)
                        <option value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label">{{ __('Duration')}}</label>
                <input type="text" class="form-control form-control-light" id="duration" name="duration" required autocomplete="off">
                <input type="hidden" name="start_date">
                <input type="hidden" name="due_date">
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label">{{ __('Description')}}</label>
                <textarea class="form-control form-control-light" id="task-description" rows="3" name="description"></textarea>
            </div>
        </div>
    </div>
        <div class="modal-footer">
          <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
          <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
        </div>

    </form>
     <link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
     <script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>

    if ($(".multi-select").length > 0) {
            $( $(".multi-select") ).each(function( index,element ) {
                var id = $(element).attr('id');
                   var multipleCancelButton = new Choices(
                        '#'+id, {
                            removeItemButton: true,
                        }
                    );
            });
       }

        $(function () {
            var start = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');
            var end = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');

            function cb(start, end) {
                $("form #duration").val(start.format('MMM D, YY hh:mm A') + ' - ' + end.format('MMM D, YY hh:mm A'));
                $('form input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
                $('form input[name="due_date"]').val(end.format('YYYY-MM-DD HH:mm:ss'));
            }

            $('form #duration').daterangepicker({
                /*autoApply: true,
                autoclose: true,*/
                autoApply: true,
                timePicker: true,
                autoUpdateInput: false,
                startDate: start,
                endDate: end,
                /*startDate: start,
                endDate: end,
                autoApply: true,
                autoclose: true,
                autoUpdateInput: false,*/
                locale: {
                    format: 'MMMM D, YYYY hh:mm A',
                    applyLabel: "{{__('Apply')}}",
                    cancelLabel: "{{__('Cancel')}}",
                    fromLabel: "{{__('From')}}",
                    toLabel: "{{__('To')}}",
                    daysOfWeek: [
                        "{{__('Sun')}}",
                        "{{__('Mon')}}",
                        "{{__('Tue')}}",
                        "{{__('Wed')}}",
                        "{{__('Thu')}}",
                        "{{__('Fri')}}",
                        "{{__('Sat')}}"
                    ],
                    monthNames: [
                        "{{__('January')}}",
                        "{{__('February')}}",
                        "{{__('March')}}",
                        "{{__('April')}}",
                        "{{__('May')}}",
                        "{{__('June')}}",
                        "{{__('July')}}",
                        "{{__('August')}}",
                        "{{__('September')}}",
                        "{{__('October')}}",
                        "{{__('November')}}",
                        "{{__('December')}}"
                    ],
                }
            }, cb);

            cb(start, end);
        });
        $(document).on('change', "select[name=project_id]", function () {
            $.get('@auth('web'){{route('home')}}@elseauth{{route('client.home')}}@endauth' + '/userProjectJson/' + $(this).val(), function (data) {
                $('select[name=assign_to]').html('');
                data = JSON.parse(data);
                $(data).each(function (i, d) {
                    $('select[name=assign_to]').append('<option value="' + d.id + '">' + d.name + ' - ' + d.email + '</option>');
                });
            });
            $.get('@auth('web'){{route('home')}}@elseauth{{route('client.home')}}@endauth' + '/projectMilestoneJson/' + $(this).val(), function (data) {
                $('select[name=milestone_id]').html('<option value="">{{__('Select Milestone')}}</option>');
                data = JSON.parse(data);
                $(data).each(function (i, d) {
                    $('select[name=milestone_id]').append('<option value="' + d.id + '">' + d.title + '</option>');
                });
            })
        })
    </script>

@else
    <div class="container mt-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>404</h1>
                        <div class="page-description">
                            {{ __('Page Not Found') }}
                        </div>
                        <div class="page-search">
                            <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                            <div class="mt-3">
                                <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
