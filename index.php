<?php

session_start();

function test_formValues($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if(!empty($_POST['todo'])){
        $task = test_formValues($_POST['todo']);

        if(!isset($_SESSION["todo"])) $_SESSION["todo"] = [];

        $_SESSION["todo"][] = $task;
    }
}

if(isset($_GET["delete"])){
    $index = intval($_GET["delete"]);
    if(isset($_SESSION["todo"][$index])){
        unset($_SESSION["todo"][$index]);
        $_SESSION["todo"] = array_values($_SESSION["todo"]);
        header("Location: index.php");
    }
}

if(isset($_POST['edit_index']) && isset($_POST['new_todo'])){
    $currentIndex = intval($_POST['edit_index']);
    $currentTodo = htmlspecialchars($_POST['new_todo']);
    if(isset($_SESSION['todo'][$currentIndex])){
        $_SESSION['todo'][$currentIndex] = $currentTodo;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Form Handling</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>

    <div class = "w-screen h-screen flex flex-col items-center p-2">

        <h1 class=" w-fit text-center text-4xl font-bold uppercase border-b-4 border-green-600" >Todo List App</h1>

        <form action="" method="post" class = "w-3/6 h-1/6 flex items-center justify-center">
            <input type="text" name="todo" placeholder="Enter todo ..." class = "outline-none border border-gray-300 hover:border-green-500 text-xl py-2 px-1 border rounded-tl-full rounded-bl-full">
            <input type="submit" value="Add Todo" class = "py-2 px-1 text-xl text-center bg-green-600 text-white border border-green-600 hover:bg-green-700 rounded-tr-2xl cursor-pointer rounded-br-2xl">
        </form>

        <div class = "w-screen flex items-center flex-col p-2">
            <div class = "w-3/6 m-auto">
                <h1 class = "w-full text-center text-4xl font-bold capitalize">My Todos</h1>

                <ul class="w-full mt-5 flex flex-col items-start justify-center space-y-1" >
                    <?php foreach($_SESSION["todo"] as $index => $todo): ?>
                    <li class="w-full h-12 px-1 text-xl bg-gray-100 hover:bg-gray-200 rounded flex justify-between items-center relative overflow-hidden" >
                        <span class="px-5" ><?php echo $todo; ?></span>
                        <div class="flex items-center justify-center space-x-2 " >
                            <div class="w-full" id="editDeleteBtn">
                                <button class=" px-2 py-2 h-full text-white text-center rounded bg-green-600" onclick="handleEditTodo(<?php echo $index;?>, '<?php echo addslashes($todo); ?>')" >Edit</button>
                                <button class=" px-2 py-2 h-full text-white text-center rounded bg-red-600" onclick="handleDeleteTodo(<?php echo $index; ?>)" >Delete</button>
                            </div>
                            <form action="" method="post" id="editForm-<?php echo $index; ?>" class="absolute top-0 left-0 bg-gray-100 hover:bg-gray-200 w-full h-full flex justify-between items-center px-4 hidden" >
                                <input type="hidden" name="edit_index" value="<?php echo $index; ?>" id = "editIndex">
                                <input type="text" name="new_todo" value="<?php echo $todo; ?>" id="newTodo" class=" w-fit bg-gray-200 border-none outline-none" >
                                <button type="submit" class=" px-2 py-2 h-full text-white text-center rounded bg-green-600">Save</button>
                            </form>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function handleDeleteTodo(index){
            if(index !== undefined){
                window.location.href = "?delete=" + index;
            }else{
                alert("Hey there were no index pass to the url")
            }
        }

        function handleEditTodo(index, currentTodo){
            if(index !== undefined && currentTodo !== undefined){
                const editForm =document.getElementById(`editForm-${index}`);
                const inputField = editForm.querySelector('#newTodo');

                editForm.style.display = 'flex';
                inputField.focus();
            }else{
                alert("Invalid ID")
            }
        }
    </script>
    
</body>
</html>