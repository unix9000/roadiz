import Vuex from 'vuex'
import {
    KEYBOARD_EVENT_SAVE
} from './mutationTypes'

// Modules
import nodesSourceSearch from './modules/nodesSourceSearchStoreModule'
import documentExplorer from './modules/documentExplorerStoreModule'
import documentWidgets from './modules/documentWidgetsStoreModule'

export default new Vuex.Store({
    modules: {
        nodesSourceSearch,
        documentExplorer,
        documentWidgets
    },
    mutations: {
        [KEYBOARD_EVENT_SAVE] () {
            // TODO
        }
    }
})
