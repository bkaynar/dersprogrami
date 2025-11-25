import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import template from './template'
import importMethod7367d2 from './import'
/**
* @see \App\Http\Controllers\MekanController::importMethod
* @see app/Http/Controllers/MekanController.php:87
* @route '/mekanlar/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/mekanlar/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MekanController::importMethod
* @see app/Http/Controllers/MekanController.php:87
* @route '/mekanlar/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::importMethod
* @see app/Http/Controllers/MekanController.php:87
* @route '/mekanlar/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::importMethod
* @see app/Http/Controllers/MekanController.php:87
* @route '/mekanlar/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::importMethod
* @see app/Http/Controllers/MekanController.php:87
* @route '/mekanlar/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/mekanlar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::index
* @see app/Http/Controllers/MekanController.php:15
* @route '/mekanlar'
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
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/mekanlar/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::create
* @see app/Http/Controllers/MekanController.php:24
* @route '/mekanlar/create'
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
* @see \App\Http\Controllers\MekanController::store
* @see app/Http/Controllers/MekanController.php:29
* @route '/mekanlar'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/mekanlar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MekanController::store
* @see app/Http/Controllers/MekanController.php:29
* @route '/mekanlar'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::store
* @see app/Http/Controllers/MekanController.php:29
* @route '/mekanlar'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::store
* @see app/Http/Controllers/MekanController.php:29
* @route '/mekanlar'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::store
* @see app/Http/Controllers/MekanController.php:29
* @route '/mekanlar'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
export const show = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/mekanlar/{mekan}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
show.url = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { mekan: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { mekan: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            mekan: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        mekan: typeof args.mekan === 'object'
        ? args.mekan.id
        : args.mekan,
    }

    return show.definition.url
            .replace('{mekan}', parsedArgs.mekan.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
show.get = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
show.head = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
const showForm = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
showForm.get = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::show
* @see app/Http/Controllers/MekanController.php:42
* @route '/mekanlar/{mekan}'
*/
showForm.head = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
export const edit = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/mekanlar/{mekan}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
edit.url = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { mekan: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { mekan: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            mekan: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        mekan: typeof args.mekan === 'object'
        ? args.mekan.id
        : args.mekan,
    }

    return edit.definition.url
            .replace('{mekan}', parsedArgs.mekan.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
edit.get = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
edit.head = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
const editForm = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
editForm.get = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\MekanController::edit
* @see app/Http/Controllers/MekanController.php:49
* @route '/mekanlar/{mekan}/edit'
*/
editForm.head = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
export const update = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/mekanlar/{mekan}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
update.url = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { mekan: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { mekan: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            mekan: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        mekan: typeof args.mekan === 'object'
        ? args.mekan.id
        : args.mekan,
    }

    return update.definition.url
            .replace('{mekan}', parsedArgs.mekan.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
update.put = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
update.patch = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
const updateForm = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
updateForm.put = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::update
* @see app/Http/Controllers/MekanController.php:56
* @route '/mekanlar/{mekan}'
*/
updateForm.patch = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\MekanController::destroy
* @see app/Http/Controllers/MekanController.php:69
* @route '/mekanlar/{mekan}'
*/
export const destroy = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/mekanlar/{mekan}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\MekanController::destroy
* @see app/Http/Controllers/MekanController.php:69
* @route '/mekanlar/{mekan}'
*/
destroy.url = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { mekan: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { mekan: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            mekan: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        mekan: typeof args.mekan === 'object'
        ? args.mekan.id
        : args.mekan,
    }

    return destroy.definition.url
            .replace('{mekan}', parsedArgs.mekan.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MekanController::destroy
* @see app/Http/Controllers/MekanController.php:69
* @route '/mekanlar/{mekan}'
*/
destroy.delete = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\MekanController::destroy
* @see app/Http/Controllers/MekanController.php:69
* @route '/mekanlar/{mekan}'
*/
const destroyForm = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\MekanController::destroy
* @see app/Http/Controllers/MekanController.php:69
* @route '/mekanlar/{mekan}'
*/
destroyForm.delete = (args: { mekan: number | { id: number } } | [mekan: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const mekanlar = {
    template: Object.assign(template, template),
    import: Object.assign(importMethod, importMethod7367d2),
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default mekanlar