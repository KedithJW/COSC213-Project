console.log("Script loaded!");
//////// board.php scripts //////////
let boardId; // declare here for global access
$(document).ready(function() {

  // Add card script
  $(".add-card-btn").click(function(){

    // Outer wrapper
    const colDiv = $("<div>").addClass("col-auto px-2");

    // Card
    const cardDiv = $("<div>").addClass("card new-card").css("width", "18rem");

    // Card header
    const cardHeader = $("<div>").addClass("card-header bg-secondary");

    // Delete card btn
    const deleteCardBtn = $('<i>').addClass("btn btn-light delete-card-btn bi bi-trash3-fill");

    // Text area
    const textArea = $("<textarea>").addClass("form-control card-name").val("--Card Name--");

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
    cardDiv.append(cardHeader, listGroup, addTaskBtn);
    colDiv.append(cardDiv);

    // Add to page
    $(this).closest(".col-auto").before(colDiv);
    //textArea.focus();
    //$("#cardContainer").append(colDiv);
  });
  

  // add task script
  $(document).on("click", ".add-task-btn", function() {

    // Find the nearest card and its list
    const card = $(this).closest(".card");
    const listGroup = card.find(".list-group");

    const newDeleteBtn = $('<i>').addClass("btn btn-light delete-task-btn bi bi-trash3-fill");
    const completeBtn = $('<i>').addClass("btn btn-light complete-task-btn bi bi-check-circle");
    const photoBtn = $('<i>').addClass("btn btn-light add-photo-btn bi bi-image");

    // Create new text area
    const newText = $('<textarea>').addClass("form-control task-name").text("Another one");
    // Eventually will implement more than just text area... so put inside a <div>
    // The div will represent the task object... childNodes are the attributes
    // So id should be assigned to div... update task then needs to use this id
    //const newTaskDiv = $('<div>').addClass("new-task");
    // Create a new task list item
    const newTask = $("<li>").addClass("list-group-item new-task");

    // Append it to the list
    newTask.append(newDeleteBtn, completeBtn, photoBtn ,newText);
    listGroup.append(newTask);
    newText.focus();
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
    .then(response => response.text())  // note: text(), not json()
    .then(cardId => {
      newCard.attr('id', cardId);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
  }

  // create-task function for eventListener below
  async function createTask(taskName, cardId, taskListItem) {
    const url = '/../COSC213-PROJECT/api/addTask.php';
    const taskData = {taskname: taskName,
                      cardid: cardId}; // seems redundant but trying to reformat for JSON

    
    fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}, // looks like directory but it's actually a MIME type
      body: JSON.stringify(taskData),
    })
    .then(response => response.text())  // note: text(), not json()
    .then(taskId => {
      taskListItem.id = taskId;
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
    const cardHeader = $("<div>").addClass("card-header bg-secondary");
    const deleteCardBtn = $('<i>').addClass("btn btn-light delete-card-btn bi bi-trash3-fill");
    const textArea = $("<textarea>").addClass("form-control card-name").val(card.name);
    const listGroup = $("<ul>").addClass("list-group list-group-flush");
    const addTaskBtn = $("<a>")
      .attr("href", "#")
      .addClass("btn btn-dark add-task-btn")
      .text("+ Add task");
    cardHeader.append(deleteCardBtn, textArea);
    cardDiv.append(cardHeader, listGroup, addTaskBtn);
    colDiv.append(cardDiv);
    $("#add-card-container").before(colDiv); // this fixes add-card button to the end
  }
  // Add task to DOM (for reading tasks from db)
  function addTaskToDom(task) {
    const card = $(`#card-${task.card_id}`);
    const status = $(task.status);
    const listGroup = card.find('.list-group');

    // Create delete btn
    const newDeleteBtn = $('<i>').addClass("btn btn-light delete-task-btn bi bi-trash3-fill");
    // Create new text area and add task name
    const newText = $('<textarea>').addClass("form-control task-name").val(task.name);
    //add new photo button
    const photoBtn = $('<i>').addClass("btn btn-light add-photo-btn bi bi-image");

    // Create a new task list item
    const newTask = $("<li>").addClass("list-group-item existing-task").attr("id", `task-${task.id}`);
    if (status[0] == 1) {
      const completeBtn = $('<i>').addClass('btn btn-primary complete-task-btn bi bi-check-circle').css('pointer-events', 'none');
      const completeLabel = $('<span>').addClass('ms-2 complete-label').text('complete');
      newTask.append(newDeleteBtn, completeBtn, completeLabel, photoBtn, newText);
      listGroup.append(newTask);
    } else {
        const completeBtn = $('<i>').addClass("btn btn-light complete-task-btn bi bi-check-circle");
        newTask.append(newDeleteBtn, completeBtn, photoBtn, newText);
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
      btn = $(completeBtn)
      btn.removeClass('btn-light').addClass('btn-primary').css('pointer-events', 'none');
      btn.after('<span class="ms-2 complete-label">complete</span>');
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
      const response = await fetch(`/../COSC213-PROJECT/api/getCards.php?boardId=${boardId}`)
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
        if(event.target 
          && event.target.classList.contains('task-name')
          && event.target.parentNode.classList.contains('new-task')) {
            const taskName = event.target.value;
            //const cardId = $(event.target).closest('div.existing-card') // hold up... have we assigned card id?
            if(taskName) {
              const cardIdString = $(event.target).closest('div.existing-card').attr('id');
              //console.log("Detected cardIdString:", cardIdString);
              const cardId = parseInt(cardIdString.replace('card-', ''), 10);
              //console.log("Detected cardId:", cardId);
              createTask(taskName, cardId, event.target.parentNode);
              //change task div class to existing-task... this will help separate CRUD operations
              event.target.parentNode.classList.replace('new-task', 'existing-task');
            }
        }
        // UPDATE Task
        else if(event.target 
          && event.target.classList.contains('task-name')
          && event.target.parentNode.classList.contains('existing-task')) {
            console.log('Updating task...')
            const idString = event.target.parentNode.id;
            const taskId = parseInt(idString.replace('task-', ''), 10);
            const taskName = event.target.value;
            if(taskName) {              
              updateTaskName(taskId, taskName);
            }
        }
        // CREATE Card
        else if(event.target 
          && event.target.classList.contains('card-name')
          && $(event.target).closest('div.new-card').length > 0) { //if length > 0 then div.new-card exists
            const cardName = event.target.value;
            if(cardName) {
              console.log(`creating card with board id ${boardId}`);
              createCard(cardName, boardId, $(event.target).closest('div.new-card'));
              $(event.target).closest('div.new-card').removeClass('new-card').addClass('existing-card');
            }
        }
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
    if(event.target 
       && event.target.classList.contains('task-name')
       && event.target.parentNode.classList.contains('existing-task')) 
    {
      event.target.readOnly = false;
      event.target.focus();
    }
    if(event.target 
       && event.target.classList.contains('card-name')
       && $(event.target).closest('div.existing-card').length > 0) 
    {
      event.target.readOnly = false;
      event.target.focus();
    }
    // DELETE Task
    if(event.target && event.target.classList.contains('delete-task-btn')) {
      const idString = event.target.parentNode.id;
      const taskId = parseInt(idString.replace('task-', ''), 10);
      deleteTask(taskId);
      deleteTaskFromDom(event.target.parentNode);
    }
    // DELETE Card
    if(event.target && event.target.classList.contains('delete-card-btn')) {
      const idString = $(event.target).closest('div.existing-card').attr('id');
      const cardId = parseInt(idString.replace('card-', ''), 10);
      deleteCard(cardId);
      deleteCardFromDom($(event.target).closest('div.col-auto'));
    }
    if(event.target && event.target.classList.contains('complete-task-btn')) {
      const idString = event.target.parentNode.id;
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

});
