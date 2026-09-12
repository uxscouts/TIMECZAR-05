import React, { useState, useEffect } from 'react';

export default function Tomatoes() {
    const [tomatoes, setTomatoes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const getCodespacesBackendUrl_02 = () => {
        const currentURL_02 = window.location.href;
        if (currentURL_02.includes('github.dev') || currentURL_02.includes('app.github.dev')) {
            return currentURL_02.replace('-3000.', '-8001.').replace(/\/$/, '');
        }
        return 'http://localhost:8000';
    };

    const BASE_URL2 = getCodespacesBackendUrl_02();
    const API_URL2 = BASE_URL2 + '/tomato.php';

    const fetchTomatoes = () => {
        fetch(API_URL2, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        })
        .then((response) => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then((data) => {
            if (Array.isArray(data)) {
                setTomatoes(data);
            } else {
                console.error("Expected array but received:", data);
                setError("Invalid data format received from server.");
            }
            setLoading(false);
        })
        .catch((err) => {
            console.error("Fetch error:", err);
            setError(err.message);
            setLoading(false);
        });
    };

    useEffect(() => {
        fetchTomatoes();
    }, []); 

    if (loading) return <div style={{ padding: '20px' }}>Loading tomato records...</div>;
    if (error) return <div style={{ color: 'red', padding: '20px' }}>Error: {error}</div>;

    return (
        <div style={{ padding: '20px', fontFamily: 'sans-serif' }}>
            <h2>Pomodoro Database Log ({tomatoes.length} entries)</h2>

            {tomatoes.length === 0 ? (
                <p>No pomodoro records found in the database.</p>
            ) : (
                <table border="1" cellPadding="10" style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left' }}>
                    <thead>
                        <tr style={{ backgroundColor: '#f2f2f2' }}>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Count</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        {tomatoes.map((tomato) => (
                            <tr key={tomato.id}>
                                <td>{tomato.id}</td>
                                <td>{tomato.title}</td>
                                <td>{tomato.count}</td>
                                <td>{tomato.category}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}
        </div>
    );
}
