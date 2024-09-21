@extends('layouts.app')

@section('title')
    Start Session
@endsection

@section('content')
    <div class="container">

        <div class="stopwatch">
            <div id="time">00:00:00</div>
            <div class="buttons">
                @if (!$data['session']->actual_start_time)
                    <button id="start_session">Start</button>
                @endif
                <button id="start" @if (!$data['session']->actual_start_time) class="d-none" @endif>Start</button>
                <button id="stop">Stop</button>
                <button id="reset">Reset</button>
            </div>
        </div>


        <div class="row text-center">
            <p class="col-md-4"><b>Session Code:</b> {{ $data['session']->code }}</p>
            <div class="col-md-2">
                <button class="btn btn-primary" id="attendanceBtn" data-bs-toggle="modal"
                    data-bs-target="#attendanceModal">Attendance</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="decisionBtn" data-bs-toggle="modal"
                    data-bs-target="#decisionModal">Decision</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="voteBtn" data-bs-toggle="modal"
                    data-bs-target="#voteModal">Vote</button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary" role="button" id="reportBtn">Report</a>
            </div>
        </div>


        <!-- Attendance Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="attendanceModalLabel">Take Attendance</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeAttendanceModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewAttendanceContent">
                        {{-- content of attendance --}}
                    </div>

                </div>
            </div>
        </div>


        <!-- Decision Modal -->
        <div class="modal fade" id="decisionModal" tabindex="-1" aria-labelledby="decisionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="decisionModalLabel">Add Decision</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeDecisionModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewDecisionContent">
                        {{-- content of Decision --}}
                    </div>

                </div>
            </div>
        </div>


        <!-- Vote Modal -->
        <div class="modal fade" id="voteModal" tabindex="-1" aria-labelledby="voteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="voteModalLabel">Add Vote</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeVoteModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewVoteContent">
                        {{-- content of Vote --}}
                    </div>

                </div>
            </div>
        </div>

    </div>

    <style>
        .stopwatch {
            display: flex;
            flex-direction: column;
            /* Stack elements vertically */
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            text-align: center;
            margin-bottom: 12rem;
        }

        #time {
            font-size: 4.5rem;
            margin-bottom: 10px;
            color: inherit;
        }

        .buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            bottom: 20px;
            width: 100%;
        }

        .buttons button {
            font-size: 20px;
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .buttons button:hover {
            transform: scale(1.1);
        }

        #start,
        #start_session {
            background-color: #4CAF50;
            color: white;
        }

        #stop {
            background-color: #f44336;
            color: white;
        }

        #reset {
            background-color: #008CBA;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            #time {
                font-size: 30vw;
            }

            .buttons button {
                font-size: 16px;
                padding: 8px 16px;
            }

            #laps li {
                font-size: 16px;
            }
        }
    </style>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sessionId = `{{ $data['session']->id }}`;

        // stopwatch logic
        $(document).ready(function() {
            let timer;
            let isRunning = false;
            let startTime;
            let elapsedTime = 0;

            const sessionCode = `{{ $data['session']->code }}`;
            const timeDisplay = document.getElementById('time');
            const startButton = document.getElementById('start');
            const stopButton = document.getElementById('stop');
            const resetButton = document.getElementById('reset');

            // Load formatted time (hours:minutes:seconds) from localStorage if available
            if (localStorage.getItem(sessionCode)) {
                timeDisplay.textContent = localStorage.getItem(sessionCode);
                elapsedTime = convertTimeToMilliseconds(localStorage.getItem(
                    sessionCode)); // Convert back to milliseconds for timer continuation
                startButton.textContent = 'Continue'; // Change button text to "Continue"
            }

            startButton.addEventListener('click', () => {
                if (!isRunning) {
                    isRunning = true;
                    startTime = Date.now() - elapsedTime;
                    timer = setInterval(() => {
                        updateTime();
                        // Save formatted time to localStorage
                        localStorage.setItem(sessionCode, timeDisplay.textContent);
                    }, 1000);
                    startButton.textContent = 'Continue'; // Change button text to "Continue"
                }
            });

            stopButton.addEventListener('click', () => {
                if (isRunning) {
                    isRunning = false;
                    clearInterval(timer);
                    elapsedTime = Date.now() - startTime;
                    // Save formatted time to localStorage
                    localStorage.setItem(sessionCode, timeDisplay.textContent);
                }
            });

            resetButton.addEventListener('click', () => {
                isRunning = false;
                clearInterval(timer);
                elapsedTime = 0;
                timeDisplay.textContent = '00:00:00';
                // Clear saved formatted time from localStorage
                localStorage.removeItem(sessionCode);
                startButton.textContent = 'Start'; // Change button text to "Start"
            });

            function updateTime() {
                const now = Date.now();
                elapsedTime = now - startTime;
                const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
                const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
                timeDisplay.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            }

            function pad(number) {
                return number < 10 ? '0' + number : number;
            }

            // Convert "hours:minutes:seconds" format back to milliseconds
            function convertTimeToMilliseconds(formattedTime) {
                const timeParts = formattedTime.split(':');
                const hours = parseInt(timeParts[0]) * 60 * 60 * 1000;
                const minutes = parseInt(timeParts[1]) * 60 * 1000;
                const seconds = parseInt(timeParts[2]) * 1000;
                return hours + minutes + seconds;
            }

            $("#start_session").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: `/sessions-departments/saveTime/${sessionId}`,
                    success: function(response) {
                        $("#start_session").addClass('d-none');
                        $('#start').click();
                        $('#start').removeClass('d-none');
                    }
                });
            });

        });



        $(document).on('click', '#attendanceBtn', function() {

            $.ajax({
                type: "GET",
                url: `/sessions-departments/fetch-attendance/${sessionId}`,
                success: function(response) {
                    $('#viewAttendanceContent').html(response);
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

        $(document).on('click', '#decisionBtn', function() {

            $.ajax({
                type: "GET",
                url: `/sessions-departments/fetch-decision/${sessionId}`,
                success: function(response) {
                    $('#viewDecisionContent').html(response);
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

        $(document).on('click', '#voteBtn', function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: `/sessions-departments/fetch-vote/${sessionId}`,
                success: function(response) {
                    $('#viewVoteContent').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    console.log(xhr.responseText);

                    // Set toastr options
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    if (xhr.status === 404) {
                        // Check if the response has a message
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                toastr.error(response.message); // Display the custom message
                            } else {
                                toastr.error("An error occurred."); // Fallback message
                            }
                        } catch (e) {
                            console.error("Failed to parse JSON response: ", e);
                            toastr.error("An unexpected error occurred.");
                        }
                    } else {
                        // Handle other status codes and parse response
                        try {
                            var response = JSON.parse(xhr.responseText);
                            var errorMessage = "";

                            if (response.errors) {
                                $.each(response.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        errorMessage +=
                                            `<div class="container">${message}<br></div>`;
                                    });
                                });
                                toastr.error(errorMessage);
                            } else {
                                toastr.error(
                                    "An unexpected error occurred."); // Fallback for other errors
                            }
                        } catch (e) {
                            console.error("Failed to parse JSON response: ", e);
                            toastr.error("An unexpected error occurred.");
                        }
                    }
                }
            });
        });

        $(document).on('click', '#reportBtn', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: `/sessions-departments/session-report/${sessionId}`,
                success: function(response) {
                    // Redirect to the session report page on success
                    window.location.href = `/sessions-departments/session-report/${sessionId}`;
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    console.log(xhr.responseText);

                    // Set toastr options
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    if (xhr.status === 404) {
                        // Check if the response has a message and display it
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                toastr.error(response.message); // Display the specific error message
                            } else {
                                toastr.error("An error occurred."); // Fallback error message
                            }
                        } catch (e) {
                            console.error("Failed to parse JSON response: ", e);
                            toastr.error("An unexpected error occurred.");
                        }
                    } else {
                        // Handle other status codes and error scenarios
                        try {
                            var response = JSON.parse(xhr.responseText);
                            var errorMessage = "";

                            if (response.errors) {
                                $.each(response.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        errorMessage +=
                                            `<div class="container">${message}<br></div>`;
                                    });
                                });
                                toastr.error(errorMessage);
                            } else {
                                toastr.error(
                                "An unexpected error occurred."); // Fallback for other errors
                            }
                        } catch (e) {
                            console.error("Failed to parse JSON response: ", e);
                            toastr.error("An unexpected error occurred.");
                        }
                    }
                }
            });
        });
    </script>
@endsection
