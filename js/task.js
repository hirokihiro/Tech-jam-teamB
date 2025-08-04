// カレンダー関係
document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#calendar-container", {
        locale: "ja",
        inline: true,
        dateFormat: "Y年m月d日",
        onChange: function (selectedDates, dateStr) {
            // 選択された日付を枠内に表示
            document.getElementById("selected-date-box").textContent = "選択日: " + dateStr;
        }
    });
});


