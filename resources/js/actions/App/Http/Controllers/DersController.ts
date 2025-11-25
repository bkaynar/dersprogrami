import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
export const downloadTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

downloadTemplate.definition = {
    methods: ["get","head"],
    url: '/dersler/template/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
downloadTemplate.url = (options?: RouteQueryOptions) => {
    return downloadTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
downloadTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
downloadTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadTemplate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
const downloadTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
downloadTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::downloadTemplate
* @see app/Http/Controllers/DersController.php:94
* @route '/dersler/template/download'
*/
downloadTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

downloadTemplate.form = downloadTemplateForm

/**
* @see \App\Http\Controllers\DersController::importMethod
* @see app/Http/Controllers/DersController.php:102
* @route '/dersler/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/dersler/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DersController::importMethod
* @see app/Http/Controllers/DersController.php:102
* @route '/dersler/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::importMethod
* @see app/Http/Controllers/DersController.php:102
* @route '/dersler/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::importMethod
* @see app/Http/Controllers/DersController.php:102
* @route '/dersler/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::importMethod
* @see app/Http/Controllers/DersController.php:102
* @route '/dersler/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/dersler',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::index
* @see app/Http/Controllers/DersController.php:14
* @route '/dersler'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/dersler/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::create
* @see app/Http/Controllers/DersController.php:38
* @route '/dersler/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\DersController::store
* @see app/Http/Controllers/DersController.php:43
* @route '/dersler'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/dersler',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DersController::store
* @see app/Http/Controllers/DersController.php:43
* @route '/dersler'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::store
* @see app/Http/Controllers/DersController.php:43
* @route '/dersler'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::store
* @see app/Http/Controllers/DersController.php:43
* @route '/dersler'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::store
* @see app/Http/Controllers/DersController.php:43
* @route '/dersler'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
export const show = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/dersler/{ders}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
show.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return show.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
show.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
show.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
const showForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
showForm.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::show
* @see app/Http/Controllers/DersController.php:56
* @route '/dersler/{ders}'
*/
showForm.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
export const edit = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/dersler/{ders}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
edit.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return edit.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
edit.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
edit.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
const editForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
editForm.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersController::edit
* @see app/Http/Controllers/DersController.php:64
* @route '/dersler/{ders}/edit'
*/
editForm.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
export const update = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/dersler/{ders}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
update.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return update.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
update.put = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
update.patch = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
const updateForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
updateForm.put = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::update
* @see app/Http/Controllers/DersController.php:71
* @route '/dersler/{ders}'
*/
updateForm.patch = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\DersController::destroy
* @see app/Http/Controllers/DersController.php:84
* @route '/dersler/{ders}'
*/
export const destroy = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/dersler/{ders}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\DersController::destroy
* @see app/Http/Controllers/DersController.php:84
* @route '/dersler/{ders}'
*/
destroy.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return destroy.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersController::destroy
* @see app/Http/Controllers/DersController.php:84
* @route '/dersler/{ders}'
*/
destroy.delete = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\DersController::destroy
* @see app/Http/Controllers/DersController.php:84
* @route '/dersler/{ders}'
*/
const destroyForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersController::destroy
* @see app/Http/Controllers/DersController.php:84
* @route '/dersler/{ders}'
*/
destroyForm.delete = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const DersController = { downloadTemplate, importMethod, index, create, store, show, edit, update, destroy, import: importMethod }

export default DersController