console.log("Script loaded!");
//////// board.php scripts //////////
let boardId; // declare here for global access
$(document).ready(function() {

  let draggedTask = null;
  // DRAG START
  $(document).on('dragstart', '.task', function () {
      draggedTask = this;
      $(this).addClass('dragging');
  });

  // DRAG END
  $(document).on('dragend', '.task', function () {
      draggedTask = null;
      $(this).removeClass('dragging');
  });

  // DRAG OVER (must prevent default)
  $(document).on('dragover', '.card', function (e) {
      e.preventDefault();
      $(this).addClass('drag-over');
  });

  // DRAG LEAVE
  $(document).on('dragleave', '.card', function () {
      $(this).removeClass('drag-over');
  });

  // DROP
  $(document).on('drop', '.card', function () {
      $(this).removeClass('drag-over');
      if (draggedTask) {
          const taskIdString = draggedTask.id;
          const taskId = parseInt(taskIdString.replace('task-', ''), 10);
          let cardIdString = $(this).attr('id');
          let cardId = parseInt(cardIdString.replace('card-', ''), 10);
          console.log(cardId);
          let taskList = $(this).find('.list-group');
          taskList.prepend(draggedTask);
          updateTaskCardId(taskId, cardId);
      }
  });
  
    // updateTaskCardId function
  async function updateTaskCardId(taskId, cardId) {
    const url = '/../COSC213-PROJECT/api/updateTaskCardId.php';
    const taskData = {taskid: taskId,
                      cardid: cardId};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, 
      body: JSON.stringify(taskData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }


  // Add card script
  $(".add-card-btn").click(function(){

    // Outer wrapper
    const colDiv = $("<div>").addClass("col-auto px-2 py-2");

    // Card
    const cardDiv = $("<div>").addClass("card new-card").css("width", "18rem");

    const cardHeader = $("<div>").addClass("card-header bg-secondary bg-gradient d-flex align-items-center gap-2");
    const deleteCardBtn = $('<i>').addClass("btn btn-secondary delete-card-btn bi bi-trash3-fill");

    // Text area
    //const textArea = $("<textarea>").addClass("form-control card-name-edit").val("--Card Name--");
    const textArea = $('<textarea>')
      .addClass("form-control card-name-edit")
      .attr({
        rows: 1,
        placeholder: "Card name...",
        maxlength: 20
      })
      // .css({ "width": "8em", "resize": "none" })
      // .on('input', function () {
      //   this.style.height = 'auto';
      //   this.style.height = this.scrollHeight + 'px';
      // })
      ;

    // List group with items
    const listGroup = $("<ul>").addClass("list-group list-group-flush");

    // don't need for now, but might be useful when populating from db
    // const tasks = ["Task 1", "Task 2", "Task 3"];
    // tasks.forEach(task => {
    //   const listItem = $("<li>").addClass("list-group-item").text(task);
    //   listGroup.append(listItem);
    // });

    // Add-task button
    const addTaskBtn = $("<a>")
      .attr("href", "#")
      .addClass("btn btn-dark add-task-btn")
      .text("+ Add task");

    cardHeader.append(deleteCardBtn, textArea);

    // Put it all together
    cardDiv.append(cardHeader, addTaskBtn, listGroup);
    colDiv.append(cardDiv);

    // Add to page
    $(this).closest(".col-auto").before(colDiv);
    textArea.focus();
    //$("#cardContainer").append(colDiv);
  });
  
    ///////////////////////////////
    // REFORMAT TASKS BEING CREATED
    ///////////////////////////////
  // add task script
  $(document).on("click", ".add-task-btn", function() {

    // Find the nearest card and its list
    const card = $(this).closest(".card");
    const listGroup = card.find(".list-group");

    const taskTopRow = $('<div>').addClass("task-top-row d-flex align-items-center gap-2 mb-2");
    const newDeleteBtn = $('<i>').addClass("btn btn-light delete-task-btn bi bi-trash3-fill");
    const completeBtn = $('<i>').addClass("btn btn-light complete-task-btn bi bi-check-circle");
    const photoBtn = $('<i>').addClass("btn btn-light add-photo-btn bi bi-image");

    // Create new text area
    const taskName = $('<textarea>')
      .addClass("form-control task-name")
      .attr({
        rows: 1,
        maxlength: 12
      })
      .css({ "width": "8em", "resize": "none" })
      .on('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
      });

    const taskDescription = $('<textarea>')
      .addClass("form-control task-description")
      .attr({
        rows: 1,
        placeholder: "Task description..."
      })
      .css({ "resize": "none" })
      .on('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
      });

    // Create a new task list item
    const newTask = $("<li>").addClass("list-group-item task new-task").attr('draggable', 'true');

    // Append it to the list
    taskTopRow.append(newDeleteBtn, completeBtn, photoBtn, taskName);
    newTask.append(taskTopRow);
    newTask.append(taskDescription);
    listGroup.prepend(newTask);
    taskName.focus();
  });

  //CREATE CARD HERE
  async function createCard(cardName, boardId, newCard) {
    const url = '/../COSC213-PROJECT/api/addCard.php';
    const cardData = {cardname: cardName,
                      boardid: boardId};

    console.log("Sending to server:", cardData);
    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, // looks like directory but it's actually a MIME type
      body: JSON.stringify(cardData),
    })
    .then(response => response.json())  // note: text(), not json()
    .then(data => {
      console.log(`Assigning card id: ${data.cardId}`);
      newCard.attr('id', data.cardId);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }

  // create-task function for eventListener below
  async function createTask(taskName, taskDescription, cardId, task) {
    const url = '/../COSC213-PROJECT/api/addTask.php';
    const taskData = {taskname: taskName,
                      description: taskDescription,
                      cardid: cardId}; // seems redundant but trying to reformat for JSON

    
    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, // looks like directory but it's actually a MIME type
      body: JSON.stringify(taskData),
    })
    .then(response => response.text())  // note: text(), not json()
    .then(taskId => {
      console.log(taskId);
      task.id = taskId;
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }

  async function deleteCard(cardId) {
    const url = '/../COSC213-PROJECT/api/deleteCard.php';
    const cardData = {cardid: cardId};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(cardData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();      
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }

  async function deleteTask(taskId) {
    const url = '/../COSC213-PROJECT/api/deleteTask.php';
    const taskData = {taskid: taskId};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(taskData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();      
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }
  // UPDATE card
  async function updateCard(cardId, cardName) {
    const url = '/../COSC213-PROJECT/api/updateCard.php';
    const cardData = {cardid: cardId,
                      cardname: cardName};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, 
      body: JSON.stringify(cardData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }
  // updateTaskName function
  async function updateTaskName(taskId, taskName) {
    const url = '/../COSC213-PROJECT/api/updateTaskName.php';
    const taskData = {taskid: taskId,
                      taskname: taskName};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, 
      body: JSON.stringify(taskData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }

  async function updateTaskDescription(taskId, taskDescription) {
    const url = '/../COSC213-PROJECT/api/updateTaskDescription.php';
    const taskData = {taskid: taskId,
                      description: taskDescription};

    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, 
      body: JSON.stringify(taskData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });    
  }

  //UPDATE Task status
  async function completeTask(taskId) {
    const url = '/../COSC213-PROJECT/api/completeTask.php';
    const taskData = {taskid: taskId}

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(taskData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }

  // Add card to DOM (for readinig cards from db)
  function addCardToDom(card) {

    const colDiv = $("<div>").addClass("col-auto px-2");
    const cardDiv = $("<div>").addClass("card existing-card").attr("id", `card-${card.id}`).css("width", "18rem");
    const cardHeader = $("<div>").addClass("card-header bg-secondary bg-gradient d-flex align-items-center gap-2");
    const deleteCardBtn = $('<i>').addClass("btn btn-secondary delete-card-btn bi bi-trash3-fill");
    const cardName = $("<div>").addClass("card-name-display").text(card.name);
    const listGroup = $("<ul>").addClass("list-group list-group-flush");
    const addTaskBtn = $("<a>")
      .attr("href", "#")
      .addClass("btn btn-dark add-task-btn")
      .text("+ Add task");
    cardHeader.append(deleteCardBtn, cardName);
    cardDiv.append(cardHeader, addTaskBtn, listGroup);
    colDiv.append(cardDiv);
    $("#add-card-container").before(colDiv); // this fixes add-card button to the end
  }
  // Add task to DOM (for reading tasks from db)
  function addTaskToDom(task) {
    const card = $(`#card-${task.card_id}`);
    const status = $(task.status);
    const listGroup = card.find('.list-group');

    const taskTopRow = $('<div>').addClass("task-top-row d-flex align-items-center gap-2 mb-2");
    // Create delete btn
    const newDeleteBtn = $('<i>').addClass("btn btn-light delete-task-btn bi bi-trash3-fill");
    // Create photo btn
    const photoBtn = $('<i>').addClass("btn btn-light add-photo-btn bi bi-image");
    // Create new text area and add task name
    const taskName = $('<div>')
      .addClass("task-name-display")
      .text(task.name)
      // .attr("rows", 1)
      // .css({ "width": "8em", "resize": "none" })
      // .on('input', function () {
      //   this.style.height = 'auto';
      //   this.style.height = this.scrollHeight + 'px';
      // })
      ;
    // Create a new task list item
    const newTask = $("<li>")
      .addClass("list-group-item task existing-task")
      .attr({id: `task-${task.id}`,
             draggable: true});
    // task description
    const taskDescription = $('<textarea>')
      .addClass("form-control task-description")
      .val(task.description)
      .attr({
        rows: 1,
        placeholder: "Task description..."
      })
      .css({ "resize": "none" })
      .on('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
      });
    //////////////////////////////
    // REFORMAT TASKS BEING LOADED
    //////////////////////////////
    if (status[0] == 1) {
      const completeBtn = $('<i>').addClass('btn btn-primary complete-task-btn bi bi-check-circle').css('pointer-events', 'none');
      const completeLabel = $('<span>').addClass('ms-2 complete-label').text('(complete)');
      taskTopRow.append(newDeleteBtn, completeBtn, photoBtn, taskName, completeLabel);
      newTask.append(taskTopRow, taskDescription); // ENDED HERE TO GO IMPLEMENT DESCRIPTION BACKEND
      listGroup.append(newTask);                   // finish for other status and it might work...
    } else {
        const completeBtn = $('<i>').addClass("btn btn-light complete-task-btn bi bi-check-circle");
        taskTopRow.append(newDeleteBtn, completeBtn, photoBtn, taskName);
        newTask.append(taskTopRow, taskDescription);
        listGroup.append(newTask);
    }
    if (task.photo) {
        const img = $('<img>')
            .attr("src", "uploads/tasks/" + task.photo)
            .addClass("mt-2 img-fluid");
        newTask.append(img);
    }

  }

  function deleteCardFromDom(cardElement) {
    if (cardElement) {
      cardElement.remove();
    }
  }

  function deleteTaskFromDom(taskElement) {
    if (taskElement) {
      taskElement.remove();
    }
  }

  function completeTaskDom(completeBtn) {
    if(completeBtn) {
      const btn = $(completeBtn)
      btn.removeClass('btn-light').addClass('btn-primary').css('pointer-events', 'none');
      btn.next().after('<span class="ms-2 complete-label">(complete)</span>'); //This adds before task name... FIX!!
      const li = btn.closest('.existing-task');
      const ul = li.parent();
      ul.append(li);
    }
  }

  //READ Tasks
  async function loadTasks(cardId) {
    try {
      console.log('Getting tasks...');
      const response = await fetch(`/../COSC213-PROJECT/api/getTasks.php?cardId=${cardId}`)
      console.log("HTTP status:", response.status);
      const data = await response.json();
      // for trouble shooting: run next two lines instead above line
      //const text = await response.text();
      //console.log("Raw response:", text);
      console.log(data.success);
      if (data.success) {
        data.tasks.forEach(task => {
          //create html element and append...
          //console.log(task.card_id);
          addTaskToDom(task);
        });
      } else {
          console.error('Failed to load tasks:', data.error);
      }
    } catch(err) {
        console.error('Error fetching tasks:', err);
    }
  }
  // READ cards
  async function loadCards(boardId) {
    try {
      console.log('Getting cards...');
      const response = await fetch(`/../COSC213-PROJECT/api/getCards.php?boardId=${boardId}`);
      const data = await response.json();

      if (data.success) {
        data.cards.forEach(card => {
          //create html element and append...
          addCardToDom(card);
          loadTasks(card.id);
        });
      } else {
          console.error('Failed to load cards:', data.error);
      }
    } catch(err) {
        console.error('Error fetching cards:', err);
    }
  }
  // READ board name
  async function loadBoardName() {
    const params = new URLSearchParams(window.location.search);
    const boardName = params.get("name");
    
    if (boardName) {
        // Decode it (in case it was URL-encoded)
        document.getElementById("board-title").textContent = decodeURIComponent(boardName);
    }
  }

  // page load event listener
  $(document).ready(() => {
    const params = new URLSearchParams(window.location.search);
    boardId = Number(params.get("id")) || 0;
    console.log(`board id: ${boardId}`);
    loadBoardName();
    loadCards(boardId);
  });

  // keydown event listener
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      event.preventDefault(); // prevents adding line break
      event.target.readOnly = true;
      event.target.blur();
        // CREATE Task
        if(event.target && event.target.classList.contains('task-name')) {
            const task = $(event.target).closest('.task');
            const taskName = event.target.value;
            if(task.hasClass('new-task')) {
                const taskDescription = task.find('.task-description').val()||'';
                const cardIdString = $(event.target).closest('div.existing-card').attr('id');
                //console.log("Detected cardIdString:", cardIdString);
                const cardId = parseInt(cardIdString.replace('card-', ''), 10);
                //console.log("Detected cardId:", cardId);
                createTask(taskName, taskDescription, cardId, task[0]);
                //change task div class to existing-task... this will help separate CRUD operations
                task.removeClass('new-task').addClass('existing-task');
            }
            // UPDATE Task
            else if(task.hasClass('existing-task')) {
                console.log('Updating task...');
                const idString = task.attr('id');
                const taskId = parseInt(idString.replace('task-', ''), 10);
                if(taskName) {              
                  updateTaskName(taskId, taskName);
                }             
            }
            const textarea = $(event.target);
            const text = textarea.val();

            const taskNameDisplay = $("<div>")
              .addClass("task-name-display")
              .text(text);

            textarea.replaceWith(taskNameDisplay);
        }
        // CREATE Card
        // else if(event.target 
        //   && event.target.classList.contains('card-name')
        //   && $(event.target).closest('div.new-card').length > 0) { //if length > 0 then div.new-card exists
        //     const cardName = event.target.value;
        //     if(cardName) {
        //       console.log(`creating card with board id ${boardId}`);
        //       createCard(cardName, boardId, $(event.target).closest('div.new-card'));
        //       $(event.target).closest('div.new-card').removeClass('new-card').addClass('existing-card');
        //     }
        // }
        // UPDATE Card
        else if(event.target 
          && event.target.classList.contains('card-name')
          && $(event.target).closest('div.existing-card').length > 0) {
            const idString = $(event.target).closest('div.existing-card').attr('id');
            const cardId = parseInt(idString.replace('card-', ''), 10);
            const cardName = event.target.value;
            if(cardName) {
              updateCard(cardId, cardName);
            }
          }    
    }
  });

  document.addEventListener('click', function(event) {
    // Handle changing task text
    if(event.target && event.target.classList.contains('task-description')) {
      event.target.readOnly = false;
      event.target.focus();
    }
    if(event.target && event.target.classList.contains('task-name-display')) {
      const text = $(event.target).text();
      const textarea = $('<textarea>')
        .addClass('form-control task-name-edit')
        .val(text)
      .attr({
        rows: 1,
        maxlength: 12
      })
        .css({ "width": "8em", "resize": "none" })
        .on("input", function () {
          this.style.height = "auto";
          this.style.height = this.scrollHeight + "px";
        });
      $(event.target).replaceWith(textarea);
      textarea.focus();
    }

    if(event.target && event.target.classList.contains('card-name-display')) {
      const text = $(event.target).text();
      const textarea = $('<textarea>')
        .addClass('form-control card-name-edit')
        .val(text)
      .attr({
        rows: 1,
        maxlength: 20
      })
        //.css({ "width": "8em", "resize": "none" })
        // .on("input", function () {
        //   this.style.height = "auto";
        //   this.style.height = this.scrollHeight + "px";
        // })
        ;
      $(event.target).replaceWith(textarea);
      textarea.focus();
    }
    // DELETE Task
    if(event.target && event.target.classList.contains('delete-task-btn')) {
      const task = event.target.closest('.existing-task');
      const idString = task.id;
      const taskId = parseInt(idString.replace('task-', ''), 10);
      deleteTask(taskId);
      deleteTaskFromDom(task);
    }
    // DELETE Card
    if(event.target && event.target.classList.contains('delete-card-btn')) {
      const idString = $(event.target).closest('div.existing-card').attr('id');
      const cardId = parseInt(idString.replace('card-', ''), 10);
      deleteCard(cardId);
      deleteCardFromDom($(event.target).closest('div.col-auto'));
    }
    if(event.target && event.target.classList.contains('complete-task-btn')) {
      const task = event.target.closest('.task');
      const idString = task.id;
      const taskId = parseInt(idString.replace('task-', ''), 10);
      completeTask(taskId);
      completeTaskDom(event.target);
    }
  });
  //Adding a photo to the task
$(document).on("click", ".add-photo-btn", function() {
    const taskItem = $(this).closest("li");
    const taskId = taskItem.attr("id").replace("task-", "");

    const fileInput = $('<input type="file" accept="image/*" style="display:none;">');
    taskItem.append(fileInput);
    fileInput.click();

    fileInput.on("change", function() {
        const file = this.files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append("taskid", taskId);
        formData.append("photo", file);

        $.ajax({
            url: "/api/uploadTaskPhoto.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success && response.photo) {
                    const img = $('<img>')
                        .attr("src", response.photo)
                        .addClass("mt-2 img-fluid");
                    taskItem.append(img);
                }
            }
        });
    });
});

  // CAN call update functions here to cover scenarios where user does not press enter
  $(document).on("blur", ".task-name-edit", function () {
    const textarea = $(this);
    const newText = textarea.val();
    const idString = $(this).closest('.task').attr('id');
    const taskId = parseInt(idString.replace('task-', ''), 10);

    const taskName = $("<div>")
      .addClass("task-name-display")
      .text(newText);

    textarea.replaceWith(taskName);
    updateTaskName(taskId, newText);
  });

  $(document).on("blur", ".task-description", function () {
    const textarea = $(this);
    const description = textarea.val();
    const idString = $(this).closest('.task').attr('id');
    const taskId = parseInt(idString.replace('task-', ''), 10);

    updateTaskDescription(taskId, description);
  });

  $(document).on("blur", ".card-name-edit", function () {
    //id? update : create
    const cardContainer = $(this).closest('div.new-card');
    const textarea = $(this);
    const cardName = textarea.val();
    const parent = $(this).closest('.card');
    const cardIdString = parent.attr('id');

    if(cardIdString) {
      cardId = parseInt(cardIdString.replace('card-', '', 10));
      updateCard(cardId, cardName);
    }
    else {
      createCard(cardName, boardId, cardContainer);
      cardContainer.removeClass('new-card').addClass('existing-card');
    }
    const cardDisplay = $('<div>')
      .addClass('card-name-display')
      .text(cardName);

    textarea.replaceWith(cardDisplay);
  })

});
