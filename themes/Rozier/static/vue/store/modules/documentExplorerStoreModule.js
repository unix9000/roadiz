import api from '../../api'
import {
    DOCUMENT_EXPLORER_REQUEST,
    DOCUMENT_EXPLORER_SUCCESS,
    DOCUMENT_EXPLORER_RESET,
    DOCUMENT_EXPLORER_FAILED,
    DOCUMENT_EXPLORER_OPEN,
    DOCUMENT_EXPLORER_CLOSE,
    DOCUMENT_EXPLORER_LOAD_MORE,
    DOCUMENT_EXPLORER_LOAD_MORE_SUCCESS,
    DOCUMENT_EXPLORER_IS_LOADED,

    KEYBOARD_EVENT_ESCAPE
} from '../mutationTypes'

/**
 * Module state
 */
const state = {
    isOpen: false,
    isLoading: false,
    isLoadingMore: false,
    documentWidget: null,
    searchTerms: null,
    documents: [],
    trans: null,
    filters: null,
    error: null,
}

/**
 * Getters
 */
const getters = {

}

/**
 * Actions
 */
const actions =  {
    documentExplorerButtonClick ({ commit }) {
        this.documentWidget()
    },
    async documentExplorerOpen ({ commit, dispatch, state }) {
        // Prevent if panel is already open
        if (state.isOpen) return

        // Open panel explorer
        commit(DOCUMENT_EXPLORER_RESET)

        // Open panel explorer
        commit(DOCUMENT_EXPLORER_OPEN)

        // Make the search
        await dispatch('documentExplorerMakeSearch')

        // Open panel explorer
        commit(DOCUMENT_EXPLORER_IS_LOADED)
    },
    documentExplorerClose ({ commit }) {
        commit(DOCUMENT_EXPLORER_CLOSE)
    },
    documentExplorerToggle ({ dispatch, state }) {
        if (state.isOpen) {
            dispatch('documentExplorerClose')
        } else {
            dispatch('documentExplorerOpen')
        }
    },
    documentExplorerUpdateSearch ({ commit, dispatch }, searchTerms = '') {
        commit(DOCUMENT_EXPLORER_REQUEST, { searchTerms })

        // Make the search
        dispatch('documentExplorerMakeSearch', searchTerms)
    },
    documentExplorerMakeSearch ({ commit }, searchTerms = '') {
        return api.getDocuments(searchTerms)
            .then((result) => {
                if (!result) {
                    commit(DOCUMENT_EXPLORER_FAILED)
                } else {
                    commit(DOCUMENT_EXPLORER_SUCCESS, { result })
                }
            })
            .catch((error) => {
                console.error(error)
                commit(DOCUMENT_EXPLORER_FAILED, { error })
            })
    },
    documentExplorerLoadMore ({ commit }) {
        commit(DOCUMENT_EXPLORER_LOAD_MORE)

        api.getDocuments(state.searchTerms, state.filters)
            .then((result) => {
                if (!result) {
                    commit(DOCUMENT_EXPLORER_FAILED)
                } else {
                    commit(DOCUMENT_EXPLORER_LOAD_MORE_SUCCESS, { result })
                }
            })
            .catch((error) => {
                console.error(error)
                commit(DOCUMENT_EXPLORER_FAILED, { error })
            })
    }
}

/**
 * Mutations
 */
const mutations = {
    [DOCUMENT_EXPLORER_REQUEST] (state, { searchTerms }) {
        state.searchTerms = searchTerms
    },
    [DOCUMENT_EXPLORER_SUCCESS] (state, { result }) {
        state.documents = result.documents
        state.documentsCount = result.documentsCount
        state.trans = result.trans
        state.filters = result.filters
    },
    [DOCUMENT_EXPLORER_LOAD_MORE] (state) {
        state.isLoadingMore = true
    },
    [DOCUMENT_EXPLORER_LOAD_MORE_SUCCESS] (state, { result }) {
        state.isLoadingMore = false
        state.documents =  [...state.documents, ...result.documents]
        state.documentsCount = result.documentsCount
        state.trans = result.trans
        state.filters = result.filters
    },
    [DOCUMENT_EXPLORER_RESET] (state) {
        state.items = []
        state.searchTerms = null
        state.filters = null
    },
    [DOCUMENT_EXPLORER_FAILED] (state, { error }) {
        state.isLoading = false
        state.isLoadingMore = false
        state.error = 'Request failed'
    },
    [DOCUMENT_EXPLORER_OPEN] (state) {
        state.isOpen = true
        state.isLoading = true
    },
    [DOCUMENT_EXPLORER_IS_LOADED] (state) {
        state.isLoading = false
    },
    [DOCUMENT_EXPLORER_CLOSE] (state) {
        state.isOpen = false
    },
    [KEYBOARD_EVENT_ESCAPE] () {
        state.isOpen = false
        state.searchTerms = null
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
