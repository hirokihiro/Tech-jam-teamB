document.addEventListener("DOMContentLoaded", function () {
  flatpickr("#calendar-container", {
    locale: "ja",
    inline: true,
    disableMobile: true,
    dateFormat: "Y年m月d日",

    // 締切日を強調表示
    onDayCreate: function (dObj, dStr, fp, dayElem) {
      const dateObj = dayElem.dateObj;
      const yyyy = dateObj.getFullYear();
      const mm = String(dateObj.getMonth() + 1).padStart(2, "0");
      const dd = String(dateObj.getDate()).padStart(2, "0");
      const dateStr = `${yyyy}-${mm}-${dd}`;
      
      if (deadlineDates.includes(dateStr)) {
        dayElem.style.backgroundColor = "#ffeaa7";
        dayElem.style.borderRadius = "50%";
      }
    },

    // 日付選択時の表示
    onChange: function (selectedDates) {
      if (!selectedDates[0]) return;

      const selected = selectedDates[0];
      const yyyy = selected.getFullYear();
      const mm = String(selected.getMonth() + 1).padStart(2, "0");
      const dd = String(selected.getDate()).padStart(2, "0");
      const isoDate = `${yyyy}-${mm}-${dd}`;

      const taskList = taskTitlesByDate[isoDate] || [];
      const box = document.getElementById("selected-date-box");

      if (taskList.length > 0) {
        box.innerHTML = `📅 ${isoDate} のタスク:<br><ul>` +
          taskList.map(title => `<li>${title}</li>`).join("") + "</ul>";
      } else {
        box.textContent = `📅 ${isoDate} にタスクはありません`;
      }

      // タスクハイライト
      const targetTask = document.querySelector(`.task[data-deadline="${isoDate}"]`);
      if (targetTask) {
        targetTask.scrollIntoView({ behavior: "smooth", block: "center" });
        targetTask.classList.add("highlight");
        setTimeout(() => targetTask.classList.remove("highlight"), 2000);
      }
    }
  });

  // フォーム表示切替
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

  // スクロールによるタスクのサイズ調整
  const taskList = document.querySelector('.task-list');
  const tasks = document.querySelectorAll('.task');

  function updateTaskScales() {
    if (!taskList) return;

    const listRect = taskList.getBoundingClientRect();
    const listTop = listRect.top;
    const listHeight = listRect.height;

    tasks.forEach(task => {
      const rect = task.getBoundingClientRect();
      const offset = rect.top - listTop;
      const relativeY = offset / listHeight;

      let scale = 1.05 - (relativeY * 0.15);
      scale = Math.max(0.9, Math.min(1.05, scale));

      let opacity = 1 - (relativeY * 0.5);
      opacity = Math.max(0.5, Math.min(1, opacity));

      task.style.setProperty('--scroll-scale', scale);
      task.style.setProperty('--scroll-opacity', opacity);
    });
  }

  if (taskList && tasks.length > 0) {
    taskList.addEventListener('scroll', updateTaskScales);
    window.addEventListener('resize', updateTaskScales);
    updateTaskScales();
  }
});