<!DOCTYPE html>
<html>
    <head>
        <title>Zadanko</title>
    </head>
    <body style="display: flex; flex-direction: column;">
        <ul>   
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul> 
        <form action="{{ route('pet.create') }}" method="POST" style="display: flex; flex-direction: column;" >
            @csrf
            <label for="name">Name</label>
            <input id="name" name="name" placeholder="doggie"/>

            <label for="category">Category name</label>
            <input id="category" name="category" placeholder="Category name" />

            <label>Photo Urls</label>
            <div id="photoUrls" ></div>
            <button type="button" onclick="addNewUrl('photoUrls', `Url of pet's image`)">Add new url</button>
            <label>Tags of pet</label>
            <div id="tags" ></div>
            <button type="button" onclick="addNewUrl('tags', `Tag of pet`)">Add new tag</button>
        
            <button type="submit">Create pet</button>
        </form>
        
        <script>
            let index = 0;

            function deleteUrl(name, id){
               const button = document.getElementById(`${name}-delete-${id}`);
               const input = document.getElementById(`${name}-${id}`);

               button.parentNode.removeChild(button);
               input.parentNode.removeChild(input);
            }

            function addNewUrl(name, placeholder){
                const container = document.getElementById(name);
                const input = document.createElement('input');
                const button = document.createElement('button');
                
                const id = index; 
                index++;

                input.id = `${name}-${id}`;
                input.placeholder = placeholder;
                input.name = `${name}[]`;

                button.innerText= `Delete field ${name}`;
                button.id= `${name}-delete-${id}`;
                button.type= "button";

                button.addEventListener('click', function() {
                    deleteUrl(name, id);
                });

                container.appendChild(input);
                container.appendChild(button);
            }

           
        </script>
    </body>
</html>
