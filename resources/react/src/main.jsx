import { createRoot } from 'react-dom/client';
import App from './App';
import './styles/index.css';

const container = document.getElementById('{{PLUGIN_SHORT}}-app');

if (container) {
    const root = createRoot(container);
    root.render(<App />);
}
