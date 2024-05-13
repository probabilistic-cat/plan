function tasksJustify() {
    var taskMinWidth = 186;
    var taskMaxWidth = 230;
    var taskSeparatorWidth = 20;
    var tasksAreaWidth = Math.floor(parseInt($('.tasks').first().width()));
    
    var tasksPerRow = Math.floor((tasksAreaWidth - taskMinWidth) / (taskMinWidth + taskSeparatorWidth) + 1);
    var remainder = (tasksAreaWidth - taskMinWidth) % (taskMinWidth + taskSeparatorWidth);
    var taskWidth = taskMinWidth + Math.floor(remainder / tasksPerRow);
    if (taskWidth > taskMaxWidth) {
        taskWidth = taskMaxWidth;
    }
    
    $('.tasks').each(function(){
        var taskNum = 0;
        $(this).find('.task').each(function(){
            taskNum++;
            if (taskNum === tasksPerRow) {
                $(this).css('margin-right', '0');
                taskNum = 0;
            } else {
                $(this).css('margin-right', '20px');
            }
            $(this).css('width', taskWidth.toString() + 'px');
        });
    });
    console.log(tasksAreaWidth);
    console.log(remainder);
    console.log(taskWidth);
}


$(document).ready(function() {
    tasksJustify();
});


$(window).resize(function() {
    tasksJustify();
});