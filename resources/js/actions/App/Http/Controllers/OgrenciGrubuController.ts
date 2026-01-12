import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
export const downloadTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

downloadTemplate.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari/template/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
downloadTemplate.url = (options?: RouteQueryOptions) => {
    return downloadTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
downloadTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
downloadTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadTemplate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
const downloadTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
*/
downloadTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::downloadTemplate
* @see app/Http/Controllers/OgrenciGrubuController.php:97
* @route '/ogrenci-gruplari/template/download'
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
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::exportMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:105
* @route '/ogrenci-gruplari/export'
*/
exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportMethod.form = exportMethodForm

/**
* @see \App\Http\Controllers\OgrenciGrubuController::importMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:110
* @route '/ogrenci-gruplari/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/ogrenci-gruplari/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::importMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:110
* @route '/ogrenci-gruplari/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::importMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:110
* @route '/ogrenci-gruplari/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::importMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:110
* @route '/ogrenci-gruplari/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::importMethod
* @see app/Http/Controllers/OgrenciGrubuController.php:110
* @route '/ogrenci-gruplari/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::index
* @see app/Http/Controllers/OgrenciGrubuController.php:15
* @route '/ogrenci-gruplari'
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
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::create
* @see app/Http/Controllers/OgrenciGrubuController.php:27
* @route '/ogrenci-gruplari/create'
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
* @see \App\Http\Controllers\OgrenciGrubuController::store
* @see app/Http/Controllers/OgrenciGrubuController.php:36
* @route '/ogrenci-gruplari'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/ogrenci-gruplari',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::store
* @see app/Http/Controllers/OgrenciGrubuController.php:36
* @route '/ogrenci-gruplari'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::store
* @see app/Http/Controllers/OgrenciGrubuController.php:36
* @route '/ogrenci-gruplari'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::store
* @see app/Http/Controllers/OgrenciGrubuController.php:36
* @route '/ogrenci-gruplari'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::store
* @see app/Http/Controllers/OgrenciGrubuController.php:36
* @route '/ogrenci-gruplari'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
export const show = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari/{ogrenciGrubu}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
show.url = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogrenciGrubu: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogrenciGrubu: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogrenciGrubu: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogrenciGrubu: typeof args.ogrenciGrubu === 'object'
        ? args.ogrenciGrubu.id
        : args.ogrenciGrubu,
    }

    return show.definition.url
            .replace('{ogrenciGrubu}', parsedArgs.ogrenciGrubu.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
show.get = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
show.head = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
const showForm = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
showForm.get = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::show
* @see app/Http/Controllers/OgrenciGrubuController.php:50
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
showForm.head = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
export const edit = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/ogrenci-gruplari/{ogrenciGrubu}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
edit.url = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogrenciGrubu: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogrenciGrubu: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogrenciGrubu: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogrenciGrubu: typeof args.ogrenciGrubu === 'object'
        ? args.ogrenciGrubu.id
        : args.ogrenciGrubu,
    }

    return edit.definition.url
            .replace('{ogrenciGrubu}', parsedArgs.ogrenciGrubu.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
edit.get = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
edit.head = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
const editForm = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
editForm.get = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::edit
* @see app/Http/Controllers/OgrenciGrubuController.php:59
* @route '/ogrenci-gruplari/{ogrenciGrubu}/edit'
*/
editForm.head = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
export const update = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/ogrenci-gruplari/{ogrenciGrubu}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
update.url = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogrenciGrubu: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogrenciGrubu: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogrenciGrubu: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogrenciGrubu: typeof args.ogrenciGrubu === 'object'
        ? args.ogrenciGrubu.id
        : args.ogrenciGrubu,
    }

    return update.definition.url
            .replace('{ogrenciGrubu}', parsedArgs.ogrenciGrubu.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
update.put = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
update.patch = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
const updateForm = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
updateForm.put = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::update
* @see app/Http/Controllers/OgrenciGrubuController.php:71
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
updateForm.patch = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\OgrenciGrubuController::destroy
* @see app/Http/Controllers/OgrenciGrubuController.php:90
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
export const destroy = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/ogrenci-gruplari/{ogrenciGrubu}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\OgrenciGrubuController::destroy
* @see app/Http/Controllers/OgrenciGrubuController.php:90
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
destroy.url = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ogrenciGrubu: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ogrenciGrubu: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ogrenciGrubu: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ogrenciGrubu: typeof args.ogrenciGrubu === 'object'
        ? args.ogrenciGrubu.id
        : args.ogrenciGrubu,
    }

    return destroy.definition.url
            .replace('{ogrenciGrubu}', parsedArgs.ogrenciGrubu.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgrenciGrubuController::destroy
* @see app/Http/Controllers/OgrenciGrubuController.php:90
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
destroy.delete = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::destroy
* @see app/Http/Controllers/OgrenciGrubuController.php:90
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
const destroyForm = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OgrenciGrubuController::destroy
* @see app/Http/Controllers/OgrenciGrubuController.php:90
* @route '/ogrenci-gruplari/{ogrenciGrubu}'
*/
destroyForm.delete = (args: { ogrenciGrubu: string | number | { id: string | number } } | [ogrenciGrubu: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const OgrenciGrubuController = { downloadTemplate, exportMethod, importMethod, index, create, store, show, edit, update, destroy, export: exportMethod, import: importMethod }

export default OgrenciGrubuController