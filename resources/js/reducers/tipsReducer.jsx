const initialState = {
    tips: [],
};

const tipsReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCH_TIPS':
            return { ...state, tips: action.payload };
        case 'LIKE_TIP':
            axios.post(`/api/tips/${action.payload}/like`)
                .then(res => {
                    return {...state, tips: state.tips.map(tip => tip.id === action.payload ? {...tip, likes: res.data.likes} : tip)};
                });
            break;
        default:
            return state;
    }
};

export default tipsReducer;
