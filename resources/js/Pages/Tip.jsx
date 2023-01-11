import { useSelector } from 'react-redux';

function Tip() {
    const tips = useSelector(state => state.tips);
    const [likes, setLikes] = useState([]); // added state to keep track of liked tips

    const handleLike = (tip) => {
        setLikes([...likes, tip.id]);
    };

    return (
        <div>
            {tips.map(tip => (
                <div key={tip.id}>
                    <h2>{tip.title}</h2>
                    <p>{tip.description}</p>
                    <button onClick={() => handleLike(tip)}>
                        {likes.includes(tip.id) ? 'Unlike' : 'Like'}
                    </button>
                </div>
            ))}
        </div>
    );
}

export default TipsList;
