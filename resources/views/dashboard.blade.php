<!DOCTYPE html>
<html>
    <head>
        <title>Zadanko</title>
    </head>
    <body style="display: flex; flex-direction: column;">
        <script>
            var index = 0;
            var tags;
            var photoUrls;
        </script>
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
            <div id="new-photoUrls-container"></div>
            <button type="button" onclick="addNewUrl('new-photoUrl', 'new-photoUrl-button', 'photoUrls[]', `Url of pet's image`, 'new-photoUrls-container', '')">Add new url</button>

            <label>Tags of pet</label>
            <div id="new-tags-container"></div>
            <button type="button" onclick="addNewUrl('new-tags', 'new-tags-button', 'tags[]', `Tag of pet`, 'new-tags-container', '')">Add new tag</button>

            <label for="status">Status</label>
            <select id="status" name="status" placeholder="Status">
                <?php
                    use App\Enums\StatusEnum;

                    foreach(StatusEnum::cases() as $item){
                        ?>
                            <option value="<?php echo $item->value; ?>"><?php echo $item->value; ?></option>
                        <?php
                    }
                ?>
            </select>
        
            <button type="submit">Create pet</button>
        </form>
                    
        <table>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Photo Url</th>
                <th>Add new Url</th>
                <th>Tags</th>
                <th>Add new Tag</th>
                <th>Status</th>
                <th>Edit</th>//TODO: dorobić edita
                <th>Delete</th>//TODO: dorobić delete
            </tr>
            @foreach ($pets as $pet)
                <tr>
                    <form action="{{ route('pet.edit') }}" method="POST">
                    @csrf
                        <input hidden name="id" value="{{ $pet->id }}"/>

                        <td>
                            {{ $pet->id }}
                        </td>
                        
                        <td>
                            @isset($pet->category->name)
                                <input name="category" value="{{ $pet->category->name }}" />
                            @else
                                <input name="category" />
                            @endisset
                        </td>

                        <td>
                            @isset($pet->name)
                                <input name="name" value="{{ $pet->name }}" />
                            @else
                                <input name="name" />  
                            @endisset
                        </td>

                        <td id="{{ $pet->id }}-photoUrls-container">
                            @foreach ($pet->photoUrls as $key => $photoUrl)
                                <input name="photoUrls[]" id="{{ $pet->id }}-photoUrls-{{ $key  }}" value="{{ $photoUrl }}" />
                                <button type="button" id="{{ $pet->id }}-photoUrls-delete-{{ $key  }}" onclick="deleteUrl('{{ $pet->id }}-photoUrls-{{ $key  }}', '{{ $pet->id }}-photoUrls-delete-{{ $key }}')">Delete url</button>
                            @endforeach
                        </td>

                        <td>
                            <button type="button" onclick="addNewUrl('{{ $pet->id }}-photoUrl', '{{ $pet->id }}-photoUrl-delete', 'photoUrls[]', `Url of pet's image`, '{{ $pet->id }}-photoUrls-container', '')">Add new url</button>
                        </td>

                        <td id="{{ $pet->id }}-tags-container">
                            @foreach ($pet->tags as $key => $tag)
                                @if (isset($tag->name))
                                    <input name="tags[]" id="{{ $pet->id }}-tags-{{ $key }}" value="{{ $tag->name }}" />
                                    <button type="button" id="{{ $pet->id }}-tags-delete-{{ $key  }}" onclick="deleteUrl('{{ $pet->id }}-tags-{{ $key  }}', '{{ $pet->id }}-tags-delete-{{ $key }}')">Delete tag</button>
                                @endif
                            @endforeach
                        </td>
                        
                        <td>
                            <button type="button" onclick="addNewUrl('{{ $pet->id }}-tags', '{{ $pet->id }}-tags-delete', 'tags[]', `Tag of pet`, '{{ $pet->id }}-tags-container', '')">Add new tag</button>
                        </td>

                        <td>
                            <select id="status" name="status" placeholder="Status" value="{{ $pet->status }}">
                                <?php
                                    foreach(StatusEnum::cases() as $item){
                                        ?>
                                            <option value="<?php echo $item->value; ?>"><?php echo $item->value; ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </td>

                        <td>
                            <button type="submit">Edit</button>
                        </td>

                    </form>
                    
                    <form action="{{ route('pet.delete') }}" method="POST">
                    @csrf
                        <td>
                            <input hidden name="id" value="{{ $pet->id }}" />
                            <button type="submit">Delete</button>
                        </td>
                    </form>

                </tr>
            @endforeach
        </table>

        <script>
            function deleteUrl(button_id, input_id){
               const button = document.getElementById(button_id);
               const input = document.getElementById(input_id);

               button.parentNode.removeChild(button);
               input.parentNode.removeChild(input);
            }

            function addNewUrl(input_id, button_id, name, placeholder, container_name, value){
                const container = document.getElementById(container_name);
                const input = document.createElement('input');
                const button = document.createElement('button');
                
                const id = index; 
                index++;

                input.id = `${input_id}-${id}`;
                input.placeholder = placeholder;
                input.name = name;
                input.value = value;

                button.innerText= `Delete field`;
                button.id= `${button_id}-${id}`;
                button.type= "button";

                button.addEventListener('click', function() {
                    deleteUrl(`${button_id}-${id}`, `${input_id}-${id}`);
                });

                container.appendChild(input);
                container.appendChild(button);
            }
        </script>
    </body>
</html>
