// ローカル日付を "YYYY-MM-DD" 形式で取得する関数
function formatDateLocal(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

document.addEventListener("DOMContentLoaded", function () {
    // カレンダー初期化
    flatpickr("#calendar", {
        locale: "ja",
        inline: true,
        disableMobile: true,
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dateStr = formatDateLocal(dayElem.dateObj); // ローカル日付で取得
            if (deadlineDates.includes(dateStr)) {
                dayElem.style.backgroundColor = "#ffeaa7";
                dayElem.style.borderRadius = "50%";
            }
        },
        onChange: function (selectedDates) {
            if (!selectedDates[0]) return;
            const isoDate = formatDateLocal(selectedDates[0]); // ローカル日付で取得
            const tasks = taskTitlesByDate[isoDate] || [];
            if (tasks.length > 0) {
                alert(`📅 ${isoDate} のタスク:\n` + tasks.join("\n"));
            } else {
                alert(`${isoDate} にタスクはありません`);
            }
        }
    });

    // チャート描画
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
