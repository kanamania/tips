import { configureStore } from 'redux-starter-kit'
import tipsReducer from './reducers/tipsReducer';

const store = configureStore({
    reducer: {
        tips: tipsReducer
    },
});

export default store;
