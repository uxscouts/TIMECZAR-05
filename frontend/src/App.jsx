import { useState } from 'react'
import Users3 from './components/Users3';
import Tomato from './components/Tomato';
import './App.css'

function App() {
  const [count, setCount] = useState(0)

  return (
    <>
      <p>User3 component</p>
      <Users3/>
      <p>Tomato component</p>
      <Tomato/>
    </>
  )
}

export default App
