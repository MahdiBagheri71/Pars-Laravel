
document.addEventListener('livewire:load', function () {

    flatpickr("#startDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });

    flatpickr("#endDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });

    flatpickr("#startTime", {
        enableTime: true,
        "locale": "fa" ,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });

    flatpickr("#endTime", {
        enableTime: true,
        "locale": "fa" ,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });
});
