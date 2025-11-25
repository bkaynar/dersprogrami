import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import template from './template'
/**
* @see \App\Http\Controllers\OgretmenController::importMethod
* @see app/Http/Controllers/OgretmenController.php:86
* @route '/ogretmenler/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/ogretmenler/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgretmenController::importMethod
* @see app/Http/Controllers/OgretmenController.php:86
* @route '/ogretmenler/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::importMethod
* @see app/Http/Controllers/OgretmenController.php:86
* @route '/ogretmenler/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::importMethod
* @see app/Http/Controllers/OgretmenController.php:86
* @route '/ogretmenler/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::importMethod
* @see app/Http/Controllers/OgretmenController.php:86
* @route '/ogretmenler/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/ogretmenler',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::index
* @see app/Http/Controllers/OgretmenController.php:14
* @route '/ogretmenler'
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
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/ogretmenler/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::create
* @see app/Http/Controllers/OgretmenController.php:23
* @route '/ogretmenler/create'
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
* @see \App\Http\Controllers\OgretmenController::store
* @see app/Http/Controllers/OgretmenController.php:28
* @route '/ogretmenler'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/ogretmenler',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgretmenController::store
* @see app/Http/Controllers/OgretmenController.php:28
* @route '/ogretmenler'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::store
* @see app/Http/Controllers/OgretmenController.php:28
* @route '/ogretmenler'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::store
* @see app/Http/Controllers/OgretmenController.php:28
* @route '/ogretmenler'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::store
* @see app/Http/Controllers/OgretmenController.php:28
* @route '/ogretmenler'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
export const show = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/ogretmenler/{ogretmen}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
show.url = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogretmen: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: typeof args.ogretmen === 'object'
        ? args.ogretmen.id
        : args.ogretmen,
    }

    return show.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
show.get = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
show.head = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
const showForm = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
showForm.get = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::show
* @see app/Http/Controllers/OgretmenController.php:41
* @route '/ogretmenler/{ogretmen}'
*/
showForm.head = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
export const edit = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/ogretmenler/{ogretmen}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
edit.url = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogretmen: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: typeof args.ogretmen === 'object'
        ? args.ogretmen.id
        : args.ogretmen,
    }

    return edit.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
edit.get = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
edit.head = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
const editForm = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
editForm.get = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenController::edit
* @see app/Http/Controllers/OgretmenController.php:48
* @route '/ogretmenler/{ogretmen}/edit'
*/
editForm.head = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
export const update = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/ogretmenler/{ogretmen}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
update.url = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogretmen: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: typeof args.ogretmen === 'object'
        ? args.ogretmen.id
        : args.ogretmen,
    }

    return update.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
update.put = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
update.patch = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
const updateForm = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
updateForm.put = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::update
* @see app/Http/Controllers/OgretmenController.php:55
* @route '/ogretmenler/{ogretmen}'
*/
updateForm.patch = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\OgretmenController::destroy
* @see app/Http/Controllers/OgretmenController.php:68
* @route '/ogretmenler/{ogretmen}'
*/
export const destroy = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/ogretmenler/{ogretmen}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\OgretmenController::destroy
* @see app/Http/Controllers/OgretmenController.php:68
* @route '/ogretmenler/{ogretmen}'
*/
destroy.url = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogretmen: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogretmen: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogretmen: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogretmen: typeof args.ogretmen === 'object'
        ? args.ogretmen.id
        : args.ogretmen,
    }

    return destroy.definition.url
            .replace('{ogretmen}', parsedArgs.ogretmen.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenController::destroy
* @see app/Http/Controllers/OgretmenController.php:68
* @route '/ogretmenler/{ogretmen}'
*/
destroy.delete = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\OgretmenController::destroy
* @see app/Http/Controllers/OgretmenController.php:68
* @route '/ogretmenler/{ogretmen}'
*/
const destroyForm = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgretmenController::destroy
* @see app/Http/Controllers/OgretmenController.php:68
* @route '/ogretmenler/{ogretmen}'
*/
destroyForm.delete = (args: { ogretmen: number | { id: number } } | [ogretmen: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const ogretmenler = {
    template: Object.assign(template, template),
    import: Object.assign(importMethod, importMethod),
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default ogretmenler