
document.addEventListener('livewire:load', function () {

    flatpickr("#startDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });

    flatpickr("#toDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });
});
