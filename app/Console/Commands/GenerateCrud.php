<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {table}';
    protected $description = 'Generate CRUD operations based on a MySQL table';

    public function handle()
    {
        $table = $this->argument('table');
        $columns = DB::getSchemaBuilder()->getColumnListing($table);

        if (empty($columns)) {
            $this->error("Table '$table' not found or has no columns.");
            return;
        }

        $model = Str::studly(Str::singular($table));
        $controller = $model . 'Controller';

        $this->generateModel($model, $columns);
        $this->generateController($model, $controller);
        $this->generateViews($model, $columns);
        $this->appendRoutes($model);

        $this->info("✅ CRUD for '$table' has been generated successfully.");
    }

    protected function generateModel($model, $columns)
    {
        $fillable = array_diff($columns, ['id', 'created_at', 'updated_at']);
        $fillableStr = implode("', '", $fillable);
        $modelTemplate = <<<EOD
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $model extends Model
{
    protected \$fillable = ['$fillableStr'];
}
EOD;

        File::ensureDirectoryExists(app_path("Models"));
        File::put(app_path("Models/$model.php"), $modelTemplate);
        $this->info("📄 Model created: $model");
    }

    //     protected function generateController($model, $controller)
//     {
//         $controllerPath = app_path("Http/Controllers/{$controller}.php");

    //         if (file_exists($controllerPath)) {
//             $this->warn("⚠️ Controller already exists: $controller");
//             return;
//         }

    //         $modelVar = Str::camel($model); // e.g. patient
//         $modelPlural = Str::plural($modelVar); // e.g. patients
//         $modelClass = "App\\Models\\$model";

    //         $controllerContent = <<<PHP
// <?php

    // namespace App\Http\Controllers;

    // use $modelClass;
// use Illuminate\Http\Request;

    // class $controller extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         \$$modelPlural = $model::latest()->paginate(10);
//         return view('$modelPlural.index', compact('$modelPlural'));
//     }

    //     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         return view('$modelPlural.create', ['mode' => 'create']);
//     }

    //     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request \$request)
//     {
//         $model::create(\$request->all());
//         return redirect(route('$modelPlural.index'))->with('success', 'Successfully created!');
//     }

    //     /**
//      * Display the specified resource.
//      */
//     public function show($model \$${modelVar})
//     {
//         return view('$modelPlural.view', compact('$modelVar'));
//     }

    //     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit($model \$${modelVar})
//     {
//         return view('$modelPlural.edit', compact('$modelVar'))->with('mode', 'edit');
//     }

    //     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request \$request, $model \$${modelVar})
//     {
//         \$${modelVar}->update(\$request->all());
//         return redirect(route('$modelPlural.index'))->with('success', 'Successfully Updated!');
//     }

    //     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy($model \$${modelVar})
//     {
//         \$${modelVar}->delete();
//         return redirect(route('$modelPlural.index'))->with('success', 'Successfully Deleted!');
//     }
// }
// PHP;

    //         file_put_contents($controllerPath, $controllerContent);
//         $this->info("📄 Fully customized controller created: $controller");
//     }



    //     protected function generateViews($model, $columns)
//     {
//         $modelSnakePlural = Str::snake(Str::plural($model)); // e.g. patients
//         $modelVar = Str::camel($model); // e.g. patient
//         $dir = resource_path("views/{$modelSnakePlural}");

    //         if (!file_exists($dir)) {
//             mkdir($dir, 0755, true);
//         }

    //         // === index.blade.php ===
//         $thead = '';
//         $tbody = '';
//         foreach ($columns as $col) {
//             $thead .= "<th>$col</th>";
//             $tbody .= "<td>{{ \$item->$col }}</td>";
//         }

    //         $indexView = <<<BLADE
// @extends('layouts.main')
// @section('content')
// <div class="container">
//     <h1>$model List</h1>
//     <a href="{{ route('$modelSnakePlural.create') }}" class="btn btn-primary mb-3">Create New</a>
//     <table class="table table-bordered">
//         <thead>
//             <tr>$thead<th>Actions</th></tr>
//         </thead>
//         <tbody>
//         @foreach (\$$modelSnakePlural as \$item)
//             <tr>$tbody
//                 <td>
//                     <a href="{{ route('$modelSnakePlural.show', \$item->id) }}" class="btn btn-sm btn-info">View</a>
//                     <a href="{{ route('$modelSnakePlural.edit', \$item->id) }}" class="btn btn-sm btn-warning">Edit</a>
//                     <form action="{{ route('$modelSnakePlural.destroy', \$item->id) }}" method="POST" style="display:inline;">
//                         @csrf
//                         @method('DELETE')
//                         <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
//                     </form>
//                 </td>
//             </tr>
//         @endforeach
//         </tbody>
//     </table>
// </div>
// @endsection
// BLADE;

    //         file_put_contents("$dir/index.blade.php", $indexView);
//         $this->info("🖼 View created: index.blade.php");

    //         // === _form.blade.php ===
//         $formFields = '';
//         foreach ($columns as $col) {
//             if (in_array($col, ['id', 'created_at', 'updated_at']))
//                 continue;

    //             $label = ucfirst(str_replace('_', ' ', $col));
//             $inputType = ($col === 'dob' || str_contains($col, 'date')) ? 'date' : 'text';

    //             $formFields .= <<<FIELD

    //         <div class="mb-2">
//             <label for="$col">$label</label>
//             <input type="$inputType" name="$col" value="{{ old('$col', \${$modelVar}->$col ?? '') }}" class="form-control">
//         </div>
// FIELD;
//         }

    //         $formView = <<<BLADE
// @csrf
// @if (\$mode === 'edit')
//     @method('PUT')
// @endif
// $formFields
// <button class="btn btn-success">{{ \$mode === 'edit' ? 'Update' : 'Create' }}</button>
// BLADE;

    //         file_put_contents("$dir/_form.blade.php", $formView);
//         $this->info("🖼 View created: _form.blade.php");

    //         // === create.blade.php ===
//         $createView = <<<BLADE
// @extends('layouts.main')
// @section('content')
//     <h2>Create $model</h2>
//     <form action="{{ route('$modelSnakePlural.store') }}" method="POST">
//         @include('$modelSnakePlural._form', ['mode' => 'create', '$modelVar' => new App\\Models\\$model])
//     </form>
// @endsection
// BLADE;

    //         file_put_contents("$dir/create.blade.php", $createView);
//         $this->info("🖼 View created: create.blade.php");

    //         // === edit.blade.php ===
//         $editView = <<<'BLADE'
// @extends('layouts.main')
// @section('content')
//     <h2>Edit {{ $model }}</h2>
//     <form action="{{ route('__ROUTE_UPDATE__', $__MODELVAR__->id) }}" method="POST">
//         @include('__INCLUDE_PATH__', ['mode' => 'edit', '__MODELVAR__' => $__MODELVAR__])
//     </form>
// @endsection
// BLADE;

    //         $editView = str_replace('__ROUTE_UPDATE__', $modelSnakePlural . '.update', $editView);
//         $editView = str_replace('__INCLUDE_PATH__', $modelSnakePlural . '._form', $editView);
//         $editView = str_replace('__MODELVAR__', $modelVar, $editView);
//         $editView = str_replace('{{ $model }}', $model, $editView);

    //         file_put_contents("$dir/edit.blade.php", $editView);


    //         // === view.blade.php ===
//         $viewFields = '';
//         foreach ($columns as $col) {
//             $label = ucfirst(str_replace('_', ' ', $col));
//             $viewFields .= <<<FIELD
//         <div class="mb-2">
//             <strong>$label:</strong> {{ \$$modelVar->$col }}
//         </div>

    // FIELD;
//         }

    //         $viewView = <<<BLADE
// @extends('layouts.main')
// @section('content')
//     <h2>View $model</h2>
// $viewFields
//     <a href="{{ route('$modelSnakePlural.index') }}" class="btn btn-secondary">Back</a>
// @endsection
// BLADE;

    //         file_put_contents("$dir/view.blade.php", $viewView);
//         $this->info("🖼 View created: view.blade.php");
//     }



    protected function generateController($model, $controller)
    {
        $modelSnakePlural = Str::snake(Str::plural($model));
        $modelVar = Str::camel($model);
        $controllerPath = app_path("Http/Controllers/{$controller}.php");

        $controllerTemplate = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Models\\$model;
use Illuminate\Http\Request;

class $controller extends Controller
{
    public function index()
    {
        \${$modelSnakePlural} = $model::latest()->paginate(10);
        return view('{$modelSnakePlural}.index', compact('{$modelSnakePlural}'));
    }

    public function create()
    {
        return view('{$modelSnakePlural}.create', ['mode' => 'create', '{$modelVar}' => new $model()]);
    }

    public function store(Request \$request)
    {
        \$data = \$request->all();

        // Handle file uploads
        foreach (\$request->files as \$field => \$file) {
            if (\$request->hasFile(\$field) && \$request->file(\$field)->isValid()) {
                \$data[\$field] = \$request->file(\$field)->store('uploads', 'public');
            }
        }

        $model::create(\$data);
        return redirect()->route('{$modelSnakePlural}.index')->with('success', 'Created successfully!');
    }

    public function show($model \${$modelVar})
    {
        return view('{$modelSnakePlural}.view', compact('{$modelVar}'));
    }

    public function edit($model \${$modelVar})
    {
        return view('{$modelSnakePlural}.edit', ['mode' => 'edit', '{$modelVar}' => \${$modelVar}]);
    }

    public function update(Request \$request, $model \${$modelVar})
    {
        \$data = \$request->all();

        // Handle file uploads
        foreach (\$request->files as \$field => \$file) {
            if (\$request->hasFile(\$field) && \$request->file(\$field)->isValid()) {
                \$data[\$field] = \$request->file(\$field)->store('uploads', 'public');
            }
        }

        \${$modelVar}->update(\$data);
        return redirect()->route('{$modelSnakePlural}.index')->with('success', 'Updated successfully!');
    }

    public function destroy($model \${$modelVar})
    {
        \${$modelVar}->delete();
        return redirect()->route('{$modelSnakePlural}.index')->with('success', 'Deleted successfully!');
    }
}
PHP;

        file_put_contents($controllerPath, $controllerTemplate);
        $this->info("📄 Fully customized controller created: {$controller}");
    }




    protected function generateViews($model, $columns)
    {
        $modelSnakePlural = Str::snake(Str::plural($model)); // e.g. patients
        $modelVar = Str::camel($model); // e.g. patient
        $dir = resource_path("views/{$modelSnakePlural}");

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Helper to detect input type based on column name
        $getInputType = function ($col) {
            $colLower = strtolower($col);
            if (in_array($colLower, ['photo', 'image', 'avatar', 'picture', 'file'])) {
                return 'file';
            }
            if (str_ends_with($colLower, '_id')) {
                return 'select';
            }
            if (str_contains($colLower, 'date') || str_contains($colLower, 'dob')) {
                return 'date';
            }
            if (str_contains($colLower, 'time')) {
                return 'time';
            }
            if (str_contains($colLower, 'email')) {
                return 'email';
            }
            if (str_contains($colLower, 'password')) {
                return 'password';
            }
            return 'text';
        };

        // === index.blade.php ===
        $thead = '';
        $tbody = '';
        foreach ($columns as $col) {
            $thead .= "<th>" . ucfirst(str_replace('_', ' ', $col)) . "</th>";
        }
        $thead .= "<th>Actions</th>";

        foreach ($columns as $col) {
            $inputType = $getInputType($col);
            if ($inputType === 'file') {
                // Show image in index view (if file column)
                $tbody .= "<td>@if(\$item->$col)<img src=\"{{ asset('storage/' . \$item->$col) }}\" alt=\"$col\" width=\"50\">@endif</td>";
            } elseif ($inputType === 'select') {
                // Show the related model name if possible (fallback to id)
                $relatedModel = Str::studly(Str::singular(str_replace('_id', '', $col)));
                $relatedVar = Str::camel($relatedModel);
                // This assumes a relation with same name exists in model, else fallback to id
                $tbody .= "<td>{{ \$item->$relatedVar->name ?? \$item->$col }}</td>";
            } else {
                $tbody .= "<td>{{ \$item->$col }}</td>";
            }
        }

        $tbody .= "<td>
        <a href=\"{{ route('{$modelSnakePlural}.show', \$item->id) }}\" class=\"btn btn-sm btn-info\">View</a>
        <a href=\"{{ route('{$modelSnakePlural}.edit', \$item->id) }}\" class=\"btn btn-sm btn-warning\">Edit</a>
        <form action=\"{{ route('{$modelSnakePlural}.destroy', \$item->id) }}\" method=\"POST\" style=\"display:inline;\">
            @csrf
            @method('DELETE')
            <button class=\"btn btn-sm btn-danger\" onclick=\"return confirm('Are you sure?')\">Delete</button>
        </form>
    </td>";

        $indexView = <<<BLADE
@extends('layouts.main')
@section('content')
<div class="container">
    <h1>{$model} List</h1>
    <a href="{{ route('{$modelSnakePlural}.create') }}" class="btn btn-primary mb-3">Create New</a>
    <table class="table table-bordered">
        <thead>
            <tr>$thead</tr>
        </thead>
        <tbody>
        @foreach (\${$modelSnakePlural} as \$item)
            <tr>$tbody</tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
BLADE;

        file_put_contents("$dir/index.blade.php", $indexView);
        $this->info("🖼 View created: index.blade.php");

        // === _form.blade.php ===
        $formFields = '';
        foreach ($columns as $col) {
            if (in_array($col, ['id', 'created_at', 'updated_at']))
                continue;

            $label = ucfirst(str_replace('_', ' ', $col));
            $inputType = $getInputType($col);

            if ($inputType === 'select') {
                // Assume you want to select from related model's list
                $relatedModel = Str::studly(Str::singular(str_replace('_id', '', $col)));
                $relatedVarPlural = Str::camel(Str::plural($relatedModel));
                $formFields .= <<<FIELD

        <div class="mb-2">
            <label for="$col">$label</label>
            <select name="$col" class="form-control">
                <option value="">Select $relatedModel</option>
                @foreach (\${$relatedVarPlural} as \$option)
                    <option value="{{ \$option->id }}" {{ old('$col', \${$modelVar}->$col ?? '') == \$option->id ? 'selected' : '' }}>{{ \$option->name ?? \$option->id }}</option>
                @endforeach
            </select>
        </div>
FIELD;
            } elseif ($inputType === 'file') {
                $formFields .= <<<FIELD

        <div class="mb-2">
            <label for="$col">$label</label>
            @if(isset(\${$modelVar}->$col) && \${$modelVar}->$col)
                <br>
                <img src="{{ asset('storage/' . \${$modelVar}->$col) }}" alt="$label" width="100">
                <br>
            @endif
            <input type="file" name="$col" class="form-control">
        </div>
FIELD;
            } else {
                $formFields .= <<<FIELD

        <div class="mb-2">
            <label for="$col">$label</label>
            <input type="$inputType" name="$col" value="{{ old('$col', \${$modelVar}->$col ?? '') }}" class="form-control">
        </div>
FIELD;
            }
        }

        // Wrap the form with csrf and method in the form view (not form tag itself)
        $formView = <<<BLADE
@csrf
@if (\$mode === 'edit')
    @method('PUT')
@endif
$formFields
<button class="btn btn-success">{{ \$mode === 'edit' ? 'Update' : 'Create' }}</button>
BLADE;

        file_put_contents("$dir/_form.blade.php", $formView);
        $this->info("🖼 View created: _form.blade.php");

        // === create.blade.php ===
        $createView = <<<BLADE
@extends('layouts.main')
@section('content')
    <h2>Create $model</h2>
    <form action="{{ route('{$modelSnakePlural}.store') }}" method="POST" enctype="multipart/form-data">
        @include('{$modelSnakePlural}._form', ['mode' => 'create', '{$modelVar}' => new App\Models\\$model])
    </form>
@endsection
BLADE;

        file_put_contents("$dir/create.blade.php", $createView);
        $this->info("🖼 View created: create.blade.php");

        // === edit.blade.php ===
        $editView = <<<BLADE
@extends('layouts.main')
@section('content')
    <h2>Edit $model</h2>
    <form action="{{ route('{$modelSnakePlural}.update', \${$modelVar}->id) }}" method="POST" enctype="multipart/form-data">
        @include('{$modelSnakePlural}._form', ['mode' => 'edit', '{$modelVar}' => \${$modelVar}])
    </form>
@endsection
BLADE;

        file_put_contents("$dir/edit.blade.php", $editView);
        $this->info("🖼 View created: edit.blade.php");

        // === view.blade.php ===
        $viewFields = '';
        foreach ($columns as $col) {
            $label = ucfirst(str_replace('_', ' ', $col));
            $inputType = $getInputType($col);

            if ($inputType === 'file') {
                $viewFields .= <<<FIELD
        <div class="mb-2">
            <strong>$label:</strong><br>
            @if(\${$modelVar}->$col)
                <img src="{{ asset('storage/' . \${$modelVar}->$col) }}" alt="$label" width="150">
            @else
                No $label
            @endif
        </div>

FIELD;
            } elseif ($inputType === 'select') {
                $relatedModel = Str::studly(Str::singular(str_replace('_id', '', $col)));
                $relatedVar = Str::camel($relatedModel);
                $viewFields .= <<<FIELD
        <div class="mb-2">
            <strong>$label:</strong> {{ \${$modelVar}->$relatedVar->name ?? \${$modelVar}->$col }}
        </div>

FIELD;
            } else {
                $viewFields .= <<<FIELD
        <div class="mb-2">
            <strong>$label:</strong> {{ \${$modelVar}->$col }}
        </div>

FIELD;
            }
        }

        $viewView = <<<BLADE
@extends('layouts.main')
@section('content')
    <h2>View $model</h2>
$viewFields
    <a href="{{ route('{$modelSnakePlural}.index') }}" class="btn btn-secondary">Back</a>
@endsection
BLADE;

        file_put_contents("$dir/view.blade.php", $viewView);
        $this->info("🖼 View created: view.blade.php");
    }




    protected function appendRoutes($model)
    {
        $routeName = Str::plural(Str::snake($model));
        $controllerClass = "App\\Http\\Controllers\\" . $model . "Controller";

        $routeEntry = "\nRoute::resource('$routeName', $controllerClass::class);";

        File::append(base_path('routes/web.php'), $routeEntry);
        $this->info("🧭 Route added for: $routeName");
    }
}
