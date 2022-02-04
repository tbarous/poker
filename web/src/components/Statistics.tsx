import React, {FunctionComponent, useEffect, useState} from "react";
import {getAPI} from "../Api";
import styled from "styled-components";

const TR = styled.tr`
  border: 1px solid;
`

const TD = styled.td`
  border: 1px solid;
`

const TABLE = styled.table`
  width: 100%;
`;

const Hand = styled.div`
  display: flex;
`

const Card = styled.div`
  margin-right: 1rem;
`

const Statistics: FunctionComponent<{}> = () => {
    const [rounds, setRounds] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getAPI().statistics().then(h => {
            setRounds(h.rounds);
            setLoading(false);
        })
    })

    return (
        <>
            {loading && "Loading data..."}

            {!loading && <TABLE>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Hands</th>
                    <th>Winner</th>
                </tr>
                </thead>
                <tbody>
                {rounds.map(round => {
                    return (
                        <TR key={round.id}>
                            <TD>
                                {round.id}
                            </TD>
                            <TD>
                                {round.hands.map(hand => {
                                    return (
                                        <Hand key={hand.id}>
                                            <Card>
                                                {hand.first_card.rank}{hand.first_card.suit}
                                            </Card>

                                            <Card>
                                                {hand.second_card.rank}{hand.second_card.suit}
                                            </Card>

                                            <Card>
                                                {hand.third_card.rank}{hand.third_card.suit}
                                            </Card>

                                            <Card>
                                                {hand.fourth_card.rank}{hand.fourth_card.suit}
                                            </Card>

                                            <Card>
                                                {hand.fifth_card.rank}{hand.fifth_card.suit}
                                            </Card>

                                            <Card>
                                                {hand.strength.name}
                                            </Card>
                                        </Hand>
                                    )
                                })}
                            </TD>

                            <TD style={{textAlign: 'center'}}>
                                {round.winner}
                            </TD>
                        </TR>
                    )
                })}
                </tbody>
            </TABLE>}
        </>
    )
}

export default Statistics;