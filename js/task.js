document.addEventListener("DOMContentLoaded", function () {
  flatpickr("#calendar-container", {
    locale: "ja",
    inline: true,
    disableMobile: true,
    dateFormat: "Y-m-d",
    onDayCreate: function (_, __, ___, dayElem) {
      const dateStr = dayElem.dateObj.toISOString().slice(0, 10);
      if (deadlineDates.includes(dateStr)) {
        dayElem.style.backgroundColor = "#ffeaa7";
        dayElem.style.borderRadius = "50%";
      }
    },
    onChange: function (selectedDates) {
      const isoDate = selectedDates[0]?.toISOString().slice(0, 10);
      const tasks = taskTitlesByDate[isoDate] || [];
      const box = document.getElementById("selected-date-box");
      if (tasks.length > 0) {
        box.innerHTML = `📅 ${isoDate} のタスク:<ul>` + tasks.map(t => `<li>${t}</li>`).join("") + "</ul>";
      } else {
        box.textContent = `📅 ${isoDate} にタスクはありません`;
      }
      const target = document.querySelector(`.task[data-deadline="${isoDate}"]`);
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "center" });
        target.classList.add("highlight");
        setTimeout(() => target.classList.remove("highlight"), 2000);
      }
    }
  });

  const showFormBtn = document.getElementById("show-form-btn");
  const cancelFormBtn = document.getElementById("cancel-form-btn");
  const floatingForm = document.getElementById("floating-form");

  if (showFormBtn && cancelFormBtn && floatingForm) {
    showFormBtn.addEventListener("click", () => floatingForm.classList.add("active"));
    cancelFormBtn.addEventListener("click", () => floatingForm.classList.remove("active"));
  }

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
