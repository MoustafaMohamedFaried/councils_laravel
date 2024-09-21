@extends('layouts.app')

@section('title')
    Session Report
@endsection

@section('content')

    @if (auth()->id() == $data['session']->responsible_id)
        <!-- take decision approval from head of department -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
            اعتماد المحضر
        </button>
    @endif

    <!-- Approval modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="approvalModalLabel"> اعتماد محضر جلسة مجلس القسم</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" id="approval">
                        <option disabled selected selected>اختر</option>
                        <option value="1">موافقة</option>
                        <option value="2">رفض</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                    <button type="button" class="btn btn-primary" id="approvalSubmitBtn">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="reportContainer container text-center" dir="rtl">

        <h1 class="mb-4">محضر الجلسة رقم {{ $data['session']->code }} لقسم {{ $data['session']->department->ar_name }}
            لكلية
            {{ $data['session']->department->faculty->ar_name }}</h1>
        {{-- <p>المنعقدة يوم {{ $data['session']-> }} {{ $data['session']-> }} هـ الموافق {{ $data['session']-> }} م</p> --}}
        <p>الحمد لله والصلاة والسلام على نبينا محمد وعلى آله وصحبه أجمعين أما بعد .</p>
        <p>
            فقد قام {{ $data['session']->createdBy->name }} بانعقاد جلسة مجلس قسم برئاسة رئيس القسم
            {{ $data['session']->responsible->name }} فى
            {{ $data['session']->place }} فى تمام الساعة {{ $data['session']->start_time->toTimeString() }} وبعضوية كلا من:
        </p>

        @if ($data['members'])
            <h2 class="mt-4">اعضاء المجلس المدعويين</h2>
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">المنصب</th>
                        <th scope="col">الحضور</th>
                    </tr>
                </thead>
                @php $x = 0; @endphp
                <tbody class="table-warning">
                    @foreach ($data['members'] as $member)
                        @php $x++; @endphp
                        <tr>
                            <th scope="row">{{ $x }}</th>
                            <td>{{ $member['user_name'] }}</td>
                            <td>{{ $member['position'] }}</td>
                            <td>{{ $member['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h2 class="mt-4">مناقشة جدول الأعمال</h2>
        <p>وتم استعراض جدول الأعمال ومناقشة ما ورد فيه واتخذ مجلس القسم القرارات والتوصيات وفق ما يلي :</p>

        @if ($data['decisions'])
            @foreach ($data['decisions'] as $main_topic => $sup_topics)
                <center>
                    <h4>{{ $main_topic }}</h4>
                </center>
                @php $i = 0; @endphp
                @foreach ($sup_topics as $sup_topic)
                    @php $i++; @endphp
                    <div style="text-align: start">
                        <h5>الموضوع {{ \App\Http\Controllers\SessionDepartmentController::arabicOrdinal($i) }} :
                            {{ $sup_topic['topic_title'] }}</h5>
                        <p>التصويت علي القرار: {{ $sup_topic['decision_status'] }}</p>
                    </div>
                @endforeach
            @endforeach
        @endif

    </div>

    <style>
        .reportContainer {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            background-color: #fff;
            text-align: center;
            margin: 0px auto 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-weight: bold;
            width: 100%;
            /* max-width: 900px; */
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: #0056b3;
            margin-bottom: 15px;
        }
    </style>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sessionId = `{{ $data['session']->id }}`;

        $("#approvalSubmitBtn").click(function(e) {
            e.preventDefault();
            var approval = $("#approval").val();
            alert(approval);
            $.ajax({
                type: "GET",
                url: `/sessions-departments/decision-approval/${sessionId}`,
                data: {
                    approval: approval
                },
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "1500",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

                    // Redirect after a short delay to allow the toastr to be visible
                    setTimeout(function() {
                        window.location.href = `/sessions-departments/${sessionId}`;
                    }, 1500); // Delay in milliseconds (match this with the timeOut value)
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    console.log(xhr.responseText);

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    // Parse the response JSON
                    var response = JSON.parse(xhr.responseText);

                    // Concatenate all error messages into a single string
                    var errorMessage = "";

                    if (response.errors) {
                        $.each(response.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                errorMessage +=
                                    `<div class="container">${message}<br></div>`;
                            });
                        });

                        // Display all error messages in a single toastr notification
                        toastr.error(errorMessage);
                    }
                }
            });
        });
    </script>
@endsection
