<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>アプリ名</h1>
    </header>

    <main class="container">
        <section class="left-panel">
            <div class="sort-tabs">
                <button class="active">優先度順</button>
                <button>期限順</button>
                <button>進捗度順</button>
            </div>

            <div class="task-list">
                <div class="task">
                    <div class="task-content"></div>
                    <button class="detail-btn">詳細</button>
                </div>
                <div class="task">
                    <div class="task-content"></div>
                    <button class="detail-btn">詳細</button>
                </div>
                <div class="task small"></div>
                <div class="task tiny"></div>
            </div>

            <div class="add-task">
                <button>＋ タスクを追加</button>
            </div>
        </section>

        <aside class="calendar">
            <div class="calendar-box">カレンダー</div>
        </aside>
    </main>
</body>
</html>
