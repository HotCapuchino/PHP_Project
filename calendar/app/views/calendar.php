<header class="header">
    <form class="logout-form" action="/logout" method="POST">
        <button class="logout-form__logout-btn">Log out</button> 
    </form>
</header>
<main class="main">
    <button class="main__addToDo-btn">Add Task</button>
    <form class="toDo-form none" action="/calendar/add" method="POST">
        <h2 class="toDo-form__title">Creating Task</h2>
        <label class="task-topic">
            <div class="task-topic__title">Task topic</div>
            <input class="task-topic__input" name="topic" type="text" placeholder="Topic">
        </label>
        <label class="task-type">
            <div class="task-type__title">Task type</div>
            <select class="taks-type__select" name="type_id">
                <?php 
                    echo '<option value="' . $tasks_types[0][0] . '" selected="selected">' . $tasks_types[0][1] . '</option>';
                    for ($i=1; $i < sizeof($tasks_types); $i++) { 
                        echo '<option value="' . $tasks_types[$i][0] . '">' . $tasks_types[$i][1] . '</option>';
                    }
                ?>
            </select>
        </label>
        <label class="task-location">
            <div class="task-location__title">Task Location</div>
            <input class="task-location__input" name="location" type="text" placeholder="Location">
        </label>
        <label class="task-date">
            <div class="task-date__title">Date and Time</div>
            <input class="task-date__input" name="date" type="date">
            <input class="task-time__input" name="time" type="time">
        </label>
        <label class="task-duration">
            <div class="task-duration__title">Task duration</div>
            <select class="task-duration__select" name="duration_id">
                <?php 
                    echo '<option value="' . $tasks_durations[0][0] . '" selected="selected">' . $tasks_durations[0][1] . '</option>';
                    for ($i=1; $i < sizeof($tasks_durations); $i++) { 
                        echo '<option value="' . $tasks_durations[$i][0] . '">' . $tasks_durations[$i][1] . '</option>';
                    }
                ?>
            </select>
        </label>
        <label class="task-comment">
            <div class="task-comment__title">Task comment</div>
            <textarea class="task-comment__comment" value="''" name="comment"></textarea>
        </label>
        <button class="toDo-form__submit-btn" type="submit">Create Task</button>
    </form>
    <div class="toDo-wrapper">
        <div class="filter-wrapper">
            <div class="status-filter-wrapper">
                <select class="status-filter-wrapper__select">
                    <?php 
                        foreach ($tasks_statuses as $value) {
                            echo '<option value="' . $value[0] . '">' . $value[1] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <input class="date-filter" type="date">
            <div class="time-filter-wrapper">
                <span class="time-filter-wrapper__option" value="today">Today</span>
                <span class="time-filter-wrapper__option" value="tomorrow">Tomorrow</span>
                <span class="time-filter-wrapper__option" value="this_week">This Week</span>
                <span class="time-filter-wrapper__option" value="next_week">Next Week</span>
            </div>
        </div>
        <div class="toDo-list-wrapper">
            <?php 
                $list_visibility = '';
                if (!sizeof($tasks)) {
                    echo '<h1 class="toDo-list-wrapper__announcement">You have no tasks...</h1>';
                    $list_visibility = ' none';
                } 
            ?>
            <ul class="toDo-list<?php echo $list_visibility?>">
                <li class="toDo-item-heading">
                    <div class="toDo-item-heading__type">Task Type</div>
                    <div class="toDo-item-heading__topic">Task Topic</div>
                    <div class="toDo-item-heading__location">Task Location</div>
                    <div class="toDo-item-heading__duration">Task Duration</div>
                    <div class="toDo-item-heading__date">Task Date</div>
                    <div class="toDo-item-heading__time">Task Time</div>
                </li>
                <?php 
                    foreach ($tasks as $key => $value) {
                        $date = explode(' ', $value[6])[0];
                        $time = explode(' ', $value[6])[1];
                        // print_r($tasks_types);
                        // echo $value[4];
                        $task_type = $tasks_types[$value[4] - 1][0];
                        $type = $tasks_types[$value[4] - 1][1];
                        $duration_type = $tasks_durations[$value[7] - 1][0];
                        $duration = $tasks_durations[$value[7] - 1][1];
                        $buttons = '';
                        foreach ($tasks_statuses as $key => $status) {
                            if ($status[0] == $value[2]) {
                                continue;
                            } else {
                                $buttons .= '<button class="toDo-buttons__' . str_replace(' ', '_', strtolower($status[1])) . '-btn" name="status_id" value="' . $status[0] . '"></button>';
                            }
                        }
                        echo(
                            '<li class="toDo-list__item">
                                <div class="toDo-item" value="' . $value[0] . '" status="' . $value[2] . '">
                                    <div class="toDo-item__type" value="' . $task_type . '">'. $type . '</div>
                                    <div class="toDo-item__topic" value="' . $value[3] . '">' . $value[3] .  '</div>
                                    <div class="toDo-item__location" value="' . $value[5] . '">' . $value[5] .  '</div>
                                    <div class="toDo-item__duration" value="' . $duration_type . '">' . $duration . '</div>
                                    <div class="toDo-item__date" value="' . $date . '">' . $date . '</div>
                                    <div class="toDo-item__time" value="' . $time . '">' . $time . '</div>
                                    <div class=" none toDo-item__comment" value="' . $value[8] . '"></div>
                                </div> 
                                <form class="toDo-buttons" action="/calendar/change-status" method="POST">
                                    <input class="none" value="' . $value[0] .'" name="id">' 
                                    . $buttons .'</form>
                                <form class="toDo-delete-form" action="/calendar/delete" method="POST">
                                    <button class="toDo-delete-form__delete-btn" value="' . $value[0] .'" name="id">Delete</button>
                                </form>
                            </li>'
                        );
                    }
                ?>
            </ul>
        </div>
    </div>
</main>
<div class="modal-wrapper none">
    <div class="modal-window">
        <div class="modal-header">
            <h3 class="modal-header__title"></h3>
            <button class="modal-header__close-btn">&#215</button>
        </div>
        <div class="modal-body">
            <div class="modal-body__message"></div>
        </div>
        <div class="modal-footer">
            <button class="modal-footer__close-btn">Close</button>
        </div>
    </div>
</div>
<script src="/public/scripts/calendar.js"></script>