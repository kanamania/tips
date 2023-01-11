import { useSelector } from 'react-redux';

function Tips() {
    const tips = useSelector(state => state.tips);
    const [comments, setComments] = useState({}); // added state to keep track of comments by tip id
    const [newComment, setNewComment] = useState("");

    useEffect(() => {
        tips.forEach(tip => {
            axios.get(`/api/tips/${tip.id}/comments`)
                .then(res => {
                    setComments({...comments, [tip.id]: res.data});
                });
        });
    }, []);

    const handleComment = (tipId) => {
        axios.post(`/api/tips/${tipId}/comments`,{
            comment: newComment
        }).then(res => {
            setComments({...comments, [tipId]:[...(comments[tipId]||[]), res.data]})
        });
    };
    const handleChangeComment = (e) => setNewComment(e.target.value);

    return (
        <div>
            {tips.map(tip => (
                <div key={tip.id}>
                    <h2>{tip.title}</h2>
                    <p>{tip.description}</p>
                    <button onClick={() => handleLike(tip)}>
                        {likes.includes(tip.id) ? 'Unlike' : 'Like'}
                    </button>
                    <div>
                        {comments[tip.id] ? comments[tip.id].map( comment => <p key={comment.id}>{comment.text}</p>) : null}
                    </div>
                    <form onSubmit={(e)=>{e.preventDefault();handleComment(tip.id)}}>
                        <textarea value={newComment} onChange={handleChangeComment}/>
                        <button type="submit">Comment</button>
                    </form>
                </div>
            ))}
        </div>
    );
}

export default Tips;
