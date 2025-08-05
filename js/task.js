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


document.addEventListener("DOMContentLoaded", function () {
  // カレンダーの初期化（既存のままでOK）
  flatpickr("#calendar-container", {
    locale: "ja",
    inline: true,
    dateFormat: "Y年m月d日",
    onChange: function (selectedDates, dateStr) {
      document.getElementById("selected-date-box").textContent = "選択日: " + dateStr;
    }
  });

  // スクロール演出
  const taskList = document.querySelector('.task-list');
  const tasks = document.querySelectorAll('.task');

  function updateTaskScales() {
    const listRect = taskList.getBoundingClientRect();
    const listTop = listRect.top;
    const listHeight = listRect.height;

    tasks.forEach(task => {
      const rect = task.getBoundingClientRect();
      const offset = rect.top - listTop;
      const relativeY = offset / listHeight;

      // スケール（大きさ）設定：上→1.02、下→0.85
      let scale = 1.02 - (relativeY * 0.2);
      scale = Math.max(0.85, Math.min(1.02, scale));

      // 透明度設定：上→1、下→0.5
      let opacity = 1 - (relativeY * 0.5);
      opacity = Math.max(0.5, Math.min(1, opacity));

      // CSS変数で渡す
      task.style.setProperty('--scroll-scale', scale);
      task.style.setProperty('--scroll-opacity', opacity);
    });
  }

  if (taskList && tasks.length > 0) {
    taskList.addEventListener('scroll', updateTaskScales);
    window.addEventListener('resize', updateTaskScales);
    updateTaskScales(); // 初期実行
  }
});



document.addEventListener("DOMContentLoaded", function () {
    const showFormBtn = document.getElementById("show-form-btn");
    const cancelFormBtn = document.getElementById("cancel-form-btn");
    const floatingForm = document.getElementById("floating-form");

    if (showFormBtn && cancelFormBtn && floatingForm) {
        showFormBtn.addEventListener("click", () => {
            floatingForm.classList.add("active");
        });

        cancelFormBtn.addEventListener("click", () => {
            floatingForm.classList.remove("active");
        });
    }
});


