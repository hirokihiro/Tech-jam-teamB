document.addEventListener("DOMContentLoaded", function () {
    // カレンダー初期化
    flatpickr("#calendar", {
        locale: "ja",
        inline: true,
        disableMobile: true,
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dateStr = dayElem.dateObj.toISOString().slice(0, 10);
            if (deadlineDates.includes(dateStr)) {
                dayElem.style.backgroundColor = "#ffeaa7";
                dayElem.style.borderRadius = "50%";
            }
        },
        onChange: function (selectedDates) {
            const isoDate = selectedDates[0]?.toISOString().slice(0, 10);
            const tasks = taskTitlesByDate[isoDate] || [];
            if (tasks.length > 0) {
                alert(`📅 ${isoDate} のタスク:\n` + tasks.join("\n"));
            } else {
                alert(`${isoDate} にタスクはありません`);
            }
        }
    });

    // チャート描画もここでOK
    const ctx = document.getElementById("statusChart").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                label: "タスク数",
                data: Object.values(statusData),
                backgroundColor: ["#f8b195", "#f9e79f", "#a8e6cf"],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'タスクのステータス統計' }
            }
        }
    });
});
