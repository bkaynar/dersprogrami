import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/grup-dersleri',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::index
* @see app/Http/Controllers/GrupDersController.php:13
* @route '/grup-dersleri'
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
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/grup-dersleri/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::create
* @see app/Http/Controllers/GrupDersController.php:28
* @route '/grup-dersleri/create'
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
* @see \App\Http\Controllers\GrupDersController::store
* @see app/Http/Controllers/GrupDersController.php:39
* @route '/grup-dersleri'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/grup-dersleri',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\GrupDersController::store
* @see app/Http/Controllers/GrupDersController.php:39
* @route '/grup-dersleri'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::store
* @see app/Http/Controllers/GrupDersController.php:39
* @route '/grup-dersleri'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GrupDersController::store
* @see app/Http/Controllers/GrupDersController.php:39
* @route '/grup-dersleri'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GrupDersController::store
* @see app/Http/Controllers/GrupDersController.php:39
* @route '/grup-dersleri'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
export const show = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/grup-dersleri/{grup}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
show.url = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grup: args }
    }

    if (Array.isArray(args)) {
        args = {
            grup: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grup: args.grup,
    }

    return show.definition.url
            .replace('{grup}', parsedArgs.grup.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
show.get = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
show.head = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
const showForm = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
showForm.get = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::show
* @see app/Http/Controllers/GrupDersController.php:61
* @route '/grup-dersleri/{grup}'
*/
showForm.head = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
export const edit = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/grup-dersleri/{grup}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
edit.url = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grup: args }
    }

    if (Array.isArray(args)) {
        args = {
            grup: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grup: args.grup,
    }

    return edit.definition.url
            .replace('{grup}', parsedArgs.grup.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
edit.get = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
edit.head = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
const editForm = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
editForm.get = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GrupDersController::edit
* @see app/Http/Controllers/GrupDersController.php:78
* @route '/grup-dersleri/{grup}/edit'
*/
editForm.head = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\GrupDersController::update
* @see app/Http/Controllers/GrupDersController.php:98
* @route '/grup-dersleri/{grup}'
*/
export const update = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/grup-dersleri/{grup}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\GrupDersController::update
* @see app/Http/Controllers/GrupDersController.php:98
* @route '/grup-dersleri/{grup}'
*/
update.url = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grup: args }
    }

    if (Array.isArray(args)) {
        args = {
            grup: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grup: args.grup,
    }

    return update.definition.url
            .replace('{grup}', parsedArgs.grup.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::update
* @see app/Http/Controllers/GrupDersController.php:98
* @route '/grup-dersleri/{grup}'
*/
update.put = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\GrupDersController::update
* @see app/Http/Controllers/GrupDersController.php:98
* @route '/grup-dersleri/{grup}'
*/
const updateForm = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GrupDersController::update
* @see app/Http/Controllers/GrupDersController.php:98
* @route '/grup-dersleri/{grup}'
*/
updateForm.put = (args: { grup: string | number } | [grup: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\GrupDersController::destroy
* @see app/Http/Controllers/GrupDersController.php:120
* @route '/grup-dersleri/{ogrenciGrupId}/{dersId}'
*/
export const destroy = (args: { ogrenciGrupId: string | number, dersId: string | number } | [ogrenciGrupId: string | number, dersId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/grup-dersleri/{ogrenciGrupId}/{dersId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\GrupDersController::destroy
* @see app/Http/Controllers/GrupDersController.php:120
* @route '/grup-dersleri/{ogrenciGrupId}/{dersId}'
*/
destroy.url = (args: { ogrenciGrupId: string | number, dersId: string | number } | [ogrenciGrupId: string | number, dersId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            ogrenciGrupId: args[0],
            dersId: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogrenciGrupId: args.ogrenciGrupId,
        dersId: args.dersId,
    }

    return destroy.definition.url
            .replace('{ogrenciGrupId}', parsedArgs.ogrenciGrupId.toString())
            .replace('{dersId}', parsedArgs.dersId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GrupDersController::destroy
* @see app/Http/Controllers/GrupDersController.php:120
* @route '/grup-dersleri/{ogrenciGrupId}/{dersId}'
*/
destroy.delete = (args: { ogrenciGrupId: string | number, dersId: string | number } | [ogrenciGrupId: string | number, dersId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\GrupDersController::destroy
* @see app/Http/Controllers/GrupDersController.php:120
* @route '/grup-dersleri/{ogrenciGrupId}/{dersId}'
*/
const destroyForm = (args: { ogrenciGrupId: string | number, dersId: string | number } | [ogrenciGrupId: string | number, dersId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GrupDersController::destroy
* @see app/Http/Controllers/GrupDersController.php:120
* @route '/grup-dersleri/{ogrenciGrupId}/{dersId}'
*/
destroyForm.delete = (args: { ogrenciGrupId: string | number, dersId: string | number } | [ogrenciGrupId: string | number, dersId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const GrupDersController = { index, create, store, show, edit, update, destroy }

export default GrupDersController