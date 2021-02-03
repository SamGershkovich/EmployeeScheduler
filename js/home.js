/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/

window.addEventListener("load", function () {

    let mainTabs = document.querySelectorAll("#main nav li")
    let profileTabs = document.querySelectorAll("#profile-main nav li")

    let logoutButton = document.getElementById("log-out");
    let availabilityTable = document.querySelector("#availability table");
    let editButton = document.getElementById("edit-button");
    let saveButton = document.getElementById("save-button");
    let checkboxes = document.querySelectorAll("#availability input[type=checkbox]");
    let openOrClose = document.querySelectorAll("#availability input[type=button]");

    let weekBackButton = $("#schedule #back");
    let weekForwardButton = $("#schedule #forward");

    let weekButtonListenerAdded = false;

    //Load all templates
    getAvailability();
    getRequests();
    getSchedule("", "week", "");
    getDepartment();
    getProfile();
    getSecurity();

    //#region General listeners

    //When the logout button is clicked
    logoutButton.addEventListener("click", function () {
        fetch("logout.php", { credentials: "include" })
            .then(response => response.text())
            .then(success);
        function success() {
            window.location.href = "index.php";
        }
    })

    //When the profile button is clicked
    $("#profile-button").click(function () {

        //Hide the main section
        $("#main").toggleClass("hidden");

        //Show the profile seciton
        $("#profile-main").toggleClass("hidden");

        //Toggle the button between the profile and home button
        $(this).toggleClass("home-button");
        let text = $("#profile-button span");
        $("#profile-button i").toggleClass("fa-user-circle").toggleClass("fa-tasks");
        if ($(text).html() == "Profile") {
            $(text).html("Home");
        } else {
            $(text).html("Profile");
        }
    });

    //For every tab:
    mainTabs.forEach(function (el) {

        //When it is clicked:
        el.addEventListener("click", function () {

            //Loop through all the mainTabs
            mainTabs.forEach(function (el) {

                //If the tab is selected:
                if (el.classList.contains("selected")) {

                    //De-select
                    el.classList.toggle("selected");
                }
            });

            let containers = document.querySelectorAll("#main .tab-container");
            containers.forEach(function (el) {
                //Hide all non hidden containers
                if (!el.classList.contains("hidden")) {
                    el.classList.toggle("hidden");
                }
            });

            //If the tab that was clicked is not already selected
            if (!this.classList.contains("selected")) {
                //Select it
                this.classList.toggle("selected");
                switch (this.innerHTML) {
                    case "Schedule":
                        document.getElementById("schedule").classList.toggle("hidden");
                        break;
                    case "Availability":
                        document.getElementById("availability").classList.toggle("hidden");
                        break;
                    case "Time-off":
                        document.getElementById("time-off").classList.toggle("hidden");
                        break;
                    case "Department":
                        document.getElementById("department").classList.toggle("hidden");
                        break;
                }
            }
        });
    });

    //For every profile tab:
    profileTabs.forEach(function (el) {

        //When it is clicked:
        el.addEventListener("click", function () {

            //Loop through all the mainTabs
            profileTabs.forEach(function (el) {

                //If the tab is selected:
                if (el.classList.contains("selected")) {

                    //De-select
                    el.classList.toggle("selected");
                }
            });

            let containers = document.querySelectorAll("#profile-main .profile-tab-container");
            containers.forEach(function (el) {
                //Hide all non hidden containers
                if (!el.classList.contains("hidden")) {
                    el.classList.toggle("hidden");
                }
            });

            //If the tab that was clicked is not already selected
            if (!this.classList.contains("selected")) {
                //Select it
                this.classList.toggle("selected");
                switch (this.innerHTML) {
                    case "Profile":
                        document.getElementById("profile").classList.toggle("hidden");
                        break;
                    case "Security":
                        document.getElementById("security").classList.toggle("hidden");
                        break;
                }
            }
        });
    });

    //#endregion

    //#region Profile

    //Get the profile template
    function getProfile() {
        fetch("templates/profile.php", { credentials: "include" })
            .then(response => response.text())
            .then(refreshProfile);
        function refreshProfile(data) {
            document.getElementById("profile").innerHTML = data;
        }
    }

    //Get the security template
    function getSecurity() {
        fetch("templates/security.php", { credentials: "include" })
            .then(response => response.text())
            .then(refreshSecurity);
        function refreshSecurity(data) {
            document.getElementById("security").innerHTML = data;
            setSecurityListeners();
        }
    }

    //Set the event listeners for the elements in the security section
    function setSecurityListeners() {

        $(".visibility").each(function () {
            $(this).click(function () {
                $(this).toggleClass("fa-eye").toggleClass("fa-eye-slash");
                let input = $(this).parent().find("input");
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                }
                else {
                    input.attr("type", "password");
                }
            });
        });

        //When the user types in the new password input
        $("#new-password").keyup(function () {

            let acceptable = true;
            let acceptableLength = true;
            let diverseCharacters = true;


            //If the length of the input is less than 8
            if (($(this).val()).length < 8) {

                acceptable = false;
                acceptableLength = false;

                //Set the status to warning and warn the user
                if (!($("#new-password-status").hasClass("fa-exclamation-triangle"))) {
                    $("#new-password-status").toggleClass("fa-check").toggleClass("fa-exclamation-triangle");
                }
                $("#status-text").html("Password must contain at least 8 characters");
            }
            //The password entered is of approriate length
            else {
                //Show the check icon
                if (!($("#new-password-status").hasClass("fa-check"))) {
                    $("#new-password-status").toggleClass("fa-check").toggleClass("fa-exclamation-triangle");
                }
                //Clear the warning text
                $("#status-text").html("");
            }

            //Use regex to check if input contains number
            if (/\d/.test($(this).val())) {

                //If the input lneght is greater than 8 and has a number
                if (diverseCharacters && acceptableLength) {

                    //Show the check icon
                    if (!($("#new-password-status").hasClass("fa-check"))) {
                        $("#new-password-status").toggleClass("fa-check").toggleClass("fa-exclamation-triangle");
                    }
                }
            }
            //The input does not contain a number
            else {
                acceptable = false;
                diverseCharacters = false;

                //Show the warning icon
                if (!($("#new-password-status").hasClass("fa-exclamation-triangle"))) {
                    $("#new-password-status").toggleClass("fa-check").toggleClass("fa-exclamation-triangle");
                }

                //Set the warning text
                $("#status-text").html("Password must contain a number");
            }

            //If the length of the input is greater than 0
            if (($(this).val()).length > 0) {

                //Show the status indicator
                if (($("#new-password-status").hasClass("hidden"))) {
                    $("#new-password-status").toggleClass("hidden");
                }
            }

            //If the length of the input is 0
            else {
                //Hide the status icon and text
                if (!($("#new-password-status").hasClass("hidden"))) {
                    $("#new-password-status").toggleClass("hidden");
                }
                //Clear the warning text
                $("#status-text").html("");
            }

            //If the new password is acceptable
            if (acceptable) {

                //Enable and clear the confirm passwod input
                $("#confirm-new-password").attr("disabled", null);
                $("#confirm-new-password").val("");

                //Show the new password status icon
                if ($("#new-password-status").hasClass("hidden")) {
                    $("#new-password-status").toggleClass("hidden");
                }
                //Clear the warning text
                $("#status-text").html("");
            }

            //The password is not acceptable
            else {
                //Disable and clear the confirm password input
                $("#confirm-new-password").attr("disabled", "true");
                $("#confirm-new-password").val("");

                //Hide the confirm password status icon
                if (!($("#confirm-password-status").hasClass("hidden"))) {
                    $("#confirm-password-status").toggleClass("hidden");
                }
            }
        });

        //When the user types in the confirm password input
        $("#confirm-new-password").keyup(function () {



            //If the passwords match
            if ($(this).val() == $("#new-password").val()) {

                //Enable the change password button
                $("#change-password-button").attr("disabled", null);

                //Swithc the status icon to the check icon
                if (($("#confirm-password-status").hasClass("fa-times"))) {
                    $("#confirm-password-status").toggleClass("fa-check").toggleClass("fa-times");
                }
                $("#status-text").html("");

            }
            //The passwords dont match
            else {
                //Disable the change password button
                $("#change-password-button").attr("disabled", "true");

                //Switch the status icon to the X icon
                if (($("#confirm-password-status").hasClass("fa-check"))) {
                    $("#confirm-password-status").toggleClass("fa-check").toggleClass("fa-times");
                }
                $("#status-text").html("Passwords don't match");

            }

            //If the input length is greater than 0
            if (($(this).val()).length > 0) {

                //Show the status icon
                if (($("#confirm-password-status").hasClass("hidden"))) {
                    $("#confirm-password-status").toggleClass("hidden");
                }
            }
            //The input length is 0
            else {
                //Hide the status icon
                if (!($("#confirm-password-status").hasClass("hidden"))) {
                    $("#confirm-password-status").toggleClass("hidden");
                }
                $("#status-text").html("");
            }

        });

        //When the change password form is submitted
        $("#change-password-form").submit(function (event) {
            event.preventDefault();

            //Change the password
            let url = "changePassword.php?id=" + $("#employee-id").val() + "&old=" + $("#old-password").val() + "&new=" + $("#new-password").val();
            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then();
        });
    }
    //#endregion

    //#region Availability

    //Get and refresh the availability template
    function getAvailability() {
        fetch("templates/availability.php", { credentials: "include" })
            .then(response => response.text())
            .then(refreshAvailability);
        function refreshAvailability(data) {
            document.getElementById("availability").innerHTML = data;
            setAvilabilityListeners();
        }
    }

    //Set the event listeners for the availability section
    function setAvilabilityListeners() {
        availabilityTable = document.querySelector("#availability table");
        editButton = document.getElementById("edit-button");
        saveButton = document.getElementById("save-button");
        checkboxes = document.querySelectorAll("#availability input[type=checkbox]");
        openOrClose = document.querySelectorAll("#availability input[type=button]");

        //When the edit button is pressed
        editButton.addEventListener("click", function () {

            //Get all the disabled elemenets
            let disabledEls = availabilityTable.querySelectorAll("input[type=time], input[type=button], input[type=checkbox]");

            //Edit is pressed, enable all inputs
            if (saveButton.disabled) {
                saveButton.disabled = false;
                this.innerHTML = "Cancel";
                disabledEls.forEach(function (el) {
                    el.disabled = false;
                });
            }

            //Cancel is pressed, refresh availability
            else {
                getAvailability();
            }
        });

        //When the save button is pressed
        saveButton.addEventListener("click", function () {

            let variables = {
                SaturdayStart: "",
                SaturdayEnd: "",
                SundayStart: "",
                SundayEnd: "",
                MondayStart: "",
                MondayEnd: "",
                TuesdayStart: "",
                TuesdayEnd: "",
                WednesdayStart: "",
                WednesdayEnd: "",
                ThursdayStart: "",
                ThursdayEnd: "",
                FridayStart: "",
                FridayEnd: ""
            };

            let weekdays = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

            let rows = availabilityTable.querySelectorAll("tbody tr")

            //For every availability row(day of the week)
            for (let i = 0; i < rows.length; i++) {

                //Store the start time that was inputted
                variables[(weekdays[i] + "Start")] = rows[i].querySelector("#" + weekdays[i] + "-start").childNodes[0].value;

                //If the start time is 'Open' or empty, the employee is available to start whenever, so store 00:00:00
                if (variables[(weekdays[i] + "Start")] == 'Open' || variables[(weekdays[i] + "Start")] == "") {
                    variables[(weekdays[i] + "Start")] = "00:00:00";
                }
                //If the start time is null, the employee is not available
                else if (variables[(weekdays[i] + "Start")] == "null") {
                    variables[(weekdays[i] + "Start")] = null;
                }

                //Store the end time that was inputted
                variables[(weekdays[i] + "End")] = rows[i].querySelector("#" + weekdays[i] + "-end").childNodes[0].value;
                //If the end time is 'Close' or empty, the employee is available to end whenever, so store 00:00:00

                if (variables[(weekdays[i] + "End")] == 'Close' || variables[(weekdays[i] + "End")] == "") {
                    variables[(weekdays[i] + "End")] = "00:00:00";
                }
                //If the start time is null, the employee is not available
                else if (variables[(weekdays[i] + "End")] == "null") {
                    variables[(weekdays[i] + "End")] = null;
                }

            }

            //Update the employees availability and then reload the template
            let url = "updateAvailability.php?saturdayStart=" + variables.SaturdayStart + "&saturdayEnd=" + variables.SaturdayEnd + "&sundayStart=" + variables.SundayStart + "&sundayEnd=" + variables.SundayEnd + "&mondayStart=" + variables.MondayStart + "&mondayEnd=" + variables.MondayEnd + "&tuesdayStart=" + variables.TuesdayStart + "&tuesdayEnd=" + variables.TuesdayEnd + "&wednesdayStart=" + variables.WednesdayStart + "&wednesdayEnd=" + variables.WednesdayEnd + "&thursdayStart=" + variables.ThursdayStart + "&thursdayEnd=" + variables.ThursdayEnd + "&fridayStart=" + variables.FridayStart + "&fridayEnd=" + variables.FridayEnd;
            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then(getAvailability);
        });

        //When an 'Open' or 'Close' button is clicked
        openOrClose.forEach(function (el) {

            //Switch the input type to time and reset its value
            el.addEventListener("click", function () {
                this.type = "time";
                this.removeAttribute("value");
            });
        });

        //When an availability checkbox is clicked
        checkboxes.forEach(function (el) {

            el.addEventListener("click", function () {

                //Get the inputs in the same row as the checkbox
                let inputs = document.querySelectorAll("#" + this.id + "-start, #" + this.id + "-end");

                //If the checkbox is checked, set the inputs to time inputs
                if (this.checked) {
                    inputs.forEach(function (el) {
                        el.innerHTML = "<input type='time'>";
                    });
                }
                //The checkboxes are unchecked, set the inputs to 'N/A' buttons
                else {
                    inputs.forEach(function (el) {
                        el.innerHTML = "<button disabled value='null'>N/A</button>";
                    });
                }
            });
        });
    }
    //#endregion

    //#region Time-off

    function getRequests() {
        fetch("templates/time_off_requests.php", { credentials: "include" })
            .then(response => response.text())
            .then(refreshRequests);
        function refreshRequests(data) {
            document.getElementById("time-off").innerHTML = data;
            setRequestsListeners();
        }
    }

    function setRequestsListeners() {

        //When a new request is submitted
        $("#new-request-form").submit(function (event) {
            event.preventDefault();

            //Store the start and end time values
            let start = new Date(this.start.value);
            let end = new Date(this.end.value);

            //If the the end time inputted is earlier than the start time
            if (end < start) {
                //Warn the user
                this.innerHTML += "<p>End date must me later then start date!</p>";
            }
            //The start and end times are valid
            else {
                //Add the request and reload the template
                let url = "addRequest.php?start=" + this.start.value + "&end=" + this.end.value + "&reason=" + this.reason.value;
                fetch(url, { credentials: "include" })
                    .then(response => response.text())
                    .then(getRequests);
            }
        });

        //When an edit request button is clicked
        $(".edit-request-button").each(function () {
            $(this).click(function () {

                //Store the id of the request
                let id = $(this).attr("id");


                let editSection = $("#time-off table").find("#" + id + "-edit");

                //Change the icons to save and cancel icons
                $(editSection).html("<i class='fas fa-save save-request' id='save-" + id + "'></i></i>");
                $(editSection).html($(editSection).html() + "<i class='fas fa-sign-out-alt' id='cancel-" + id + "'></i>");

                //When the cancel button is clicked, reload the template
                $(editSection).find("#cancel-" + id).click(function () {
                    getRequests();
                });

                //When the save button is clicked
                $(".save-request").each(function (el) {
                    $(this).click(function () {

                        //Store the start and end values
                        let start = this.parentNode.parentNode.querySelector(".start").value;
                        let end = this.parentNode.parentNode.querySelector(".end").value;

                        //Create the url
                        let url = "updateRequest.php?id=" + (this.id).substring((this.id).indexOf("request-") + 8) + "&start=" + start + "&end=" + end;

                        //Update the request
                        fetch(url, { credentials: "include" })
                            .then(response => response.text())
                            .then(getRequests);
                    });
                });

                //Find the date inputes and enable them
                $(editSection).parent().find("input[type=date]").each(function () {
                    $(this).attr("disabled", null);
                    $(this).toggleClass("request-input");
                });
            });
        });

        //When a delete button is clicked
        document.querySelectorAll(".delete-request").forEach(function (el) {
            el.addEventListener("click", function () {

                //Create the url
                let url = "deleteRequest.php?id=" + (this.id).substring((this.id).indexOf("delete-") + 7);

                //Delete the request
                fetch(url, { credentials: "include" })
                    .then(response => response.text())
                    .then(getRequests);
            });
        });

    }
    //#endregion

    //#region Schedule

    //When the window is resized refresh the schedule template
    window.addEventListener("resize", function () {
        refreshSchedule();
    });

    //Get the schedule template
    //Param: direction = the direction to shift the displayed time period
    //Param: period = Whether to display in week or day more
    //Param: date = the date to display
    function getSchedule(direction, period, date = "") {

        //Build the url
        let url = "templates/schedule.php?direction=" + direction + "&period=" + period + "&date=" + date;

        //Get the schedule
        fetch(url, { credentials: "include" })
            .then(response => response.text())
            .then(refreshSchedule);
    }

    //Refresh the shedule display
    function refreshSchedule(data) {

        //If this was called by an ajax request, add listeners(other will result in duplicate listeners)
        if (data != null) {
            weekButtonListenerAdded = false;
        }

        $("#schedule").html(data);

        //6 am
        let storeOpen = 6;
        //9 pm
        let storeClose = 21;


        //For every time slot
        $(".time-slot").each(function () {


            let timeBlock = $(this).children().first();
            let hourSizeIncrement = $(this).width() / (storeClose - storeOpen);
            let timeStart = $(this).children().first().children().first().val();
            let timeEnd = $(this).children().first().children().first().next().val();

            let startHour = 0;
            let startMin = 0;

            let endHour = 0;
            let endMin = 0;

            let startMinuteShift = 0;
            let endMinuteShift = 0;


            //If the start and end times are defined(there are shifts)
            if (timeStart != undefined && timeEnd != undefined) {

                startHour = parseInt(timeStart.substring(0, 2)) - storeOpen;
                startMin = parseInt(timeStart.substring(3, 5));

                endHour = parseInt(timeEnd.substring(0, 2)) - storeOpen;
                endMin = parseInt(timeEnd.substring(3, 5));

                startMinuteShift = (startMin / 30) * (hourSizeIncrement / 2);
                endMinuteShift = (endMin / 30) * (hourSizeIncrement / 2);

                $(timeBlock).css("left", ((startHour) * (hourSizeIncrement)) + startMinuteShift)
                    .css("width", ((endHour - startHour) * hourSizeIncrement) - (startMinuteShift + endMinuteShift));
            }

            //Clear the inner html
            this.innerHTML = "";

            //If there are start and end time inputs(there are shifts)
            if ($(timeBlock).children().length > 0) {
                //Add the start and time inputs to maintain shift boundaries
                $(this).append(timeBlock);
            }

            //For every hour within the store open hours
            for (i = 0; i < (storeClose - storeOpen) + 1; i++) {

                let spanClass = "";
                let displayHour = i + 6;
                let meridian = " am";
                let minutes = ":00";

                //If the current hour is within the start and end hour and the start time and end time is set: this hour is within the shift
                if (i >= startHour && i <= endHour && (timeStart != undefined && timeEnd != undefined)) {

                    spanClass = "hour-active";

                    //Set the start and end minutes
                    if (i == startHour) {
                        minutes = ":" + startMin;
                    }
                    else if (i == endHour) {
                        minutes = ":" + endMin;
                    }
                }
                //The hour is not within the shift
                else {
                    spanClass = "hour";
                }

                //If the hour is past noon(convert from 24h)
                if (displayHour > 12) {
                    displayHour -= 12;
                }

                //If the current hour is not the start or end hour
                if (i > startHour && i < endHour) {
                    spanClass += " hour-mid";
                }

                this.innerHTML += "<span class='" + spanClass + "'>" + displayHour + minutes + "</span>";
            }
        });

        //If the listeners have not been added yet
        if (!weekButtonListenerAdded) {

            //Add the listeners
            setScheduleListeners();
        }
    }

    //Reload the available employee select element
    function refreshEmployeeSelect(data) {

        $("#available-employees").html(data);
    }

    //Set the listeners for the schedule section
    function setScheduleListeners() {
        weekBackButton = $("#schedule #back");
        weekForwardButton = $("#schedule #forward");
        weekNowButton = $("#schedule #this-week");
        weekViewButton = $("#week-view");

        //If the week back button is clicked
        $(weekBackButton).click(function () {

            //Get the schedule and shift back
            getSchedule("back");
        });

        //If the week forward button is clicked
        $(weekForwardButton).click(function () {

            //Get the schedule and shift forwad
            getSchedule("forward");
        });

        //If the now today button is clicked
        $(weekNowButton).click(function () {

            //Get the schedule and reset the shift
            getSchedule("now");
        });

        //If the week view button is clicked
        $(weekViewButton).click(function () {

            //get the current date the schedule was at
            let dayViewDate = $(".day-view-date").attr("id");

            //Get the schedule in week mode
            getSchedule(null, "week", dayViewDate);
        });


        //When a day slot is clicked
        $(".day-slot").each(function () {

            //Get the schedule in day mode at the day that was clicked
            $(this).click(function () {
                getSchedule("", "day", this.id);
            });
        });

        //When a delete shift button is clicked
        $(".delete-shift").each(function () {

            //Delete the shift
            $(this).click(function () {
                let id = $(this).attr("id");
                let url = "deleteShift.php?id=" + id.substring(id.indexOf("shift-") + 6);
                fetch(url, { credentials: "include" })
                    .then(response => response.text())
                    .then(getSchedule);
            });
        });


        //When the new shift start time input is un focued, reload the employee select element
        $("#new-shift-start-time").blur(function () {

            let url = "getAvailableEmployees.php?date=" + $("#date-range").find("span").attr("id") + "&start=" + $("#new-shift-start-time").val() + "&end=" + $("#new-shift-end-time").val();
            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then(refreshEmployeeSelect);

        });

        //When the new shift end time input is un focued, reload the employee select element
        $("#new-shift-end-time").blur(function () {
            let url = "getAvailableEmployees.php?date=" + $("#date-range").find("span").attr("id") + "&start=" + $("#new-shift-start-time").val() + "&end=" + $("#new-shift-end-time").val();

            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then(refreshEmployeeSelect);

        });


        //When the new shift form is submitted
        $("#new-shift-form").submit(function (event) {
            event.preventDefault();

            let url = "addShift.php?id=" + $("#available-employees").val() + "&date=" + $("#date-range").find("span").attr("id") + "&start=" + $("#new-shift-start-time").val() + "&end=" + $("#new-shift-end-time").val();
            console.log(url);
            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then(getSchedule);
        });


        weekButtonListenerAdded = true;
    }
    //#endregion

    //#region Department
    function getDepartment() {
        fetch("templates/department.php", { credentials: "include" })
            .then(response => response.text())
            .then(refreshDepartment);
        function refreshDepartment(data) {
            document.getElementById("department").innerHTML = data;
            setDepartmentListeners();
        }
    }
    function setDepartmentListeners() {
        $(".edit-employee-button").each(function () {
            $(this).click(function () {
                let id = $(this).attr("id");
                let select = $(this).parent().parent().find("select");
                let status = $("#" + id + "-edit");

                $(select).attr("disabled", null);

                status.html("<i class='fas fa-save save-employee' id='save-" + id + "'></i></i>");

                status.html(status.html() + "<i class='fas fa-sign-out-alt' id='cancel-" + id + "'></i>");

                $("#cancel-" + id).click(function () {
                    getDepartment();
                })

                $(".save-employee").each(function () {
                    $(this).click(function () {
                        let select = $(this).parent().parent().find("select");
                        let id = $(this).attr("id");
                        let url = "updateEmployee.php?id=" + id.substring(id.indexOf("employee-") + 9) + "&position=" + $(select).val();

                        fetch(url, { credentials: "include" })
                            .then(response => response.text())
                            .then(getDepartment);
                    });
                });
            });
        });

        $(".delete-employee").each(function () {
            $(this).click(function () {
                let id = $(this).attr("id");
                let url = "deleteEmployee.php?id=" + id.substring(id.indexOf("delete-") + 7);
                fetch(url, { credentials: "include" })
                    .then(response => response.text())
                    .then(getDepartment);
            });
        });

        $("#new-employee-form").submit(function (event) {
            event.preventDefault();
            let name = $("#new-employee-name").val();
            let number = $("#new-employee-number").val();
            let position = $("#new-employee-position").val();
            let department = $("#new-employee-department").val();

            let url = "addEmployee.php?number=" + number + "&name=" + name + "&position=" + position + "&department=" + department;
            fetch(url, { credentials: "include" })
                .then(response => response.text())
                .then(getDepartment);
        });

    }
    //#endregion

});